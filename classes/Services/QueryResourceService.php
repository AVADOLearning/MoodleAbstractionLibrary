<?php

namespace Avado\MoodleAbstractionLibrary\Services;

use Avado\MoodleAbstractionLibrary\Database\Builder;
use Symfony\Component\HttpFoundation\Request;
use Avado\MoodleAbstractionLibrary\Routing\Controller\Controller;
use Avado\MoodleAbstractionLibrary\Traits\ChecksUserIsPrivileged;

/**
 * Class QueryResourceService
 *
 * @package Avado\MoodleAbstractionLibrary\Services
 */
class QueryResourceService
{
    use ChecksUserIsPrivileged;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @const string
     */
    protected const URL_DOT_REPLACEMENT = '||||||';

    /**
     * @var int
     */
    protected $loadCount = 0;

    /**
     * @var string
     */
    protected $route;

    /**
     * QueryResourceService constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $model
     * @param Controller $controller
     * @return Builder|mixed
     * @throws \Exception
     */
    public function search(string $model, Controller $controller)
    {
        $this->route = 'search';
        ($model)::setPrivileged($this->isStaff($this->request));
        $queryParameters = $this->getConstraints();
        $resources = $this->queryConstraints(($model)::query(), $queryParameters[0], [$model]);
        $resources = $this->addRelationshipsToSearch($resources, $queryParameters[1], $model);
        $resources = method_exists($controller, 'filterSearch') ? $controller->filterSearch($resources) : $resources;
        return $this->addSortByToSearch($resources);
    }

    /**
     * @param $resources
     * @param string $model
     * @return mixed
     * @throws \Exception
     */
    public function get($resources, string $model)
    {
        $this->route = 'get';
        return $this->addRelationshipsToSearch($resources, $this->getConstraints()[1], $model);
    }

    /**
     * This function segregates the main resource fields
     * and relation fields that have been searched for.
     *
     * It also strips out the parameters that are not a field of the table
     *
     * @return array
     */
    protected function getConstraints(): array
    {
        $urlSearchKeywords = ['page','offset','limit','relationships','strict', 'sortBy', 'sortOrder'];
        $queryParameters = $this->getQueryParameters();

        $relationParameters = $this->getRelationParameters($queryParameters);
        return [
            array_diff_key($queryParameters, array_flip($urlSearchKeywords), $relationParameters),
            $relationParameters
        ];
    }

    /**
     * This get the query string and passes ahead to process
     *
     * @return array
     */
    protected function getQueryParameters(): array
    {
        return $this->parseQueryString($this->request->server->get("QUERY_STRING"));
    }

    /**
     * Currently symfony replaces "." with "_" as it uses the parse_str function
     * With this function we make sure that we are not letting it do so.
     *
     * @param string $data
     * @return array
     */
    protected function parseQueryString(string $data): array
    {
        parse_str(str_replace(".", self::URL_DOT_REPLACEMENT, $data), $params);
        foreach ($params as $key => $value) {
            $key = str_replace(self::URL_DOT_REPLACEMENT, ".", $key);
            $value = str_replace(self::URL_DOT_REPLACEMENT, ".", $value);
            $queryParameters[$key] = $value;
        }
        return $queryParameters ?? [];
    }

    /**
     * The fields associated with the relationship are identified
     *
     * @param array $queryParameters
     * @return array
     */
    protected function getRelationParameters(array $queryParameters): array
    {
        foreach ($queryParameters as $key => $value) {
            if (strpos($key, '.') !== false) {
                $relationParameters[$key] = $value;
            }
        }

        return $relationParameters ?? [];
    }

    /**
     * @param $query
     * @param array $where
     * @param array $model
     * @return mixed
     * @throws \Exception
     */
    protected function queryConstraints($query, array $where, array $model)
    {
        foreach ($where as $key => $value) {
            if ($condition = $this->pullOperatorFromParameterKey($key, $model)) {
                $query->where(...$condition);
            } else {
                if (!$this->fieldIsSearchable($key, $model)) {
                    throw new \Exception("Field is not searchable");
                }
                $field = $this->prependTableName($key, $this->getFieldModel($key, $model));
                if (strpos($value, ',') !== false) {
                    $query->whereIn($field, explode(',', $value));
                } else {
                    $query->where($field, $value);
                }
            }
        }
        return $query;
    }

    /**
     * @param string $key
     * @param array|null $models
     * @return array|bool
     * @throws \Exception
     */
    protected function pullOperatorFromParameterKey(string $key, array $models = null)
    {
        $operators = [
            '!=',
            'LIKE',
            '>',
            '<',
            '>=',
            '<='
        ];

        $key = \html_entity_decode($key);

        foreach ($operators as $operator) {
            if (strpos($key, $operator) !== false) {
                $condition = explode($operator, $key);

                if (!$this->fieldIsSearchable($condition[0], $models)) {
                    throw new \Exception("Field is not searchable");
                }
                $field = $this->prependTableName($condition[0], $this->getFieldModel($condition[0], $models));
                return [$field,$operator,$condition[1]];
            }
        }
        return false;
    }

    /**
     * In hasManyThrough relationship there are two models used
     * So here the model is identified
     *
     * @param string $field
     * @param array|null $models
     * @return string
     */
    protected function getFieldModel(string $field, array $models = null): string
    {
        return strpos($field, 'pivot.') !== false ? $models[1]: $models[0];
    }

    /**
     * @param string $field
     * @param string $model
     * @return string
     */
    protected function prependTableName(string $field, string $model): string
    {
        return (new $model)->getTable() . '.' . str_replace('pivot.', '', $field);
    }

    /**
     * Fields are checked if they are allowed to be searched
     *
     * @param string $field
     * @param array|null $models
     * @return bool
     */
    protected function fieldIsSearchable(string $field, array $models = null): bool
    {
        $model = $this->getFieldModel($field, $models);
        $field = strpos($field, 'pivot.') !== false ? explode('.', $field)[1]: $field;
        return defined("$model::SEARCH_FIELDS") && in_array($field, $model::SEARCH_FIELDS);
    }

    /**
     * Eloquent has different variations of querying data
     * Eg. with, load, whereHas, whereDoesntHave
     *
     * Strict is true it will check whereHas
     * Strict is inverse it will check whereDoesntHave
     * Strict should only be used for Search and not Get route of controller
     *
     * Search route call, calls "with" irrespective of strict being used
     * Get route calls "load" for first relation and "with" for the next
     *
     * Check Eloquent doc for how with, load, whereHas or whereDoesntHave works
     *
     * @param $resources
     * @param array $relationParameters
     * @param $model
     * @return mixed
     * @throws \Exception
     */
    protected function addRelationshipsToSearch($resources, array $relationParameters, $model)
    {
        if ($relationships = $this->request->get('relationships')) {
            if ($strictMode = $this->request->get('strict', false)) {
                $resources = $this->getRelations($model, $resources, $relationships, $relationParameters, $strictMode);
            }
            $resources = $this->getRelations($model, $resources, $relationships, $relationParameters, false);
        }
        return $resources;
    }

    /**
     * Eager Loading Multiple Relationships
     * "," separated are multiple relations for the main resource
     *
     * @param $model
     * @param $resources
     * @param string $relationships
     * @param array $relationParameters
     * @param bool $strictMode
     * @return mixed
     * @throws \Exception
     */
    protected function getRelations(
        $model,
        $resources,
        string $relationships,
        array $relationParameters,
        $strictMode = false
    ) {
        $relationParameters = $this->formatRelationParameters($relationParameters);
        foreach (explode(',', $relationships) as $relation) {
            $this->loadCount = 0;
            $resources = $this->processDynamicNestedConstraintLoading(
                $resources,
                $relation,
                $relationParameters,
                [$model],
                $strictMode
            );
        }
        return $resources;
    }

    /**
     * The relation and column name are separated here
     *
     * @param array $relationParameters
     * @return array
     */
    protected function formatRelationParameters(array $relationParameters): array
    {
        foreach ($relationParameters as $key => $value) {
            $separatorPosition = $this->getSeparatorPosition($key);
            $entireRelation = substr($key, 0, $separatorPosition);
            $column = substr($key, $separatorPosition + 1);
            $parameters[$entireRelation][$column] = $value;
        }
        return $parameters ?? [];
    }

    /**
     * @param string $field
     * @return int
     */
    protected function getSeparatorPosition(string $field): int
    {
        $separatorPosition = strrpos($field, ".");
        if (strpos($field, 'pivot') !== false) {
            $separatorPosition = strrpos($field, ".", $separatorPosition - strlen($field) - 1);
        }
        return $separatorPosition;
    }

    /**
     * Eloquent Nested Contraints Loading Process starts here
     * Each relation is processed and they are broken into
     * the relation and child relations
     *
     * @param $resources
     * @param string $relation
     * @param array $relationParameters
     * @param array $models
     * @param bool $strictMode
     * @param string $prevRelation
     * @return mixed
     * @throws \Exception
     */
    protected function processDynamicNestedConstraintLoading(
        $resources,
        string $relation,
        array $relationParameters,
        array $models,
        $strictMode = false,
        string $prevRelation = ''
    ) {
        $relation = $this->breakChildRelations($relation);

        $relationModels = $this->getRelationModels($relation[0], $models);
        $relationKey = ($prevRelation) ? $prevRelation . '.' . $relation[0] : $relation[0];
        $relationWhere = $relationParameters[$relationKey] ?? [];
        $this->checkRelationDefined($relationWhere, $relationModels, $relationKey);

        return $this->dynamicNestedConstraintLoading(
            $resources,
            $relation[0],
            $relationWhere,
            $relation[1],
            $relationParameters,
            $relationModels,
            $relationKey,
            $strictMode
        );
    }

    /**
     * Each relation are broken into
     * the relation and child relations
     *
     * @param string $relation
     * @return array
     */
    protected function breakChildRelations(string $relation): array
    {
        $childRelations = '';
        if (strpos($relation, '.') !== false) {
            $childRelations = substr($relation, strpos($relation, '.') + 1);
            $relation = substr($relation, 0, strpos($relation, '.'));
        }
        return [$relation, $childRelations];
    }

    /**
     * Resource children are checked for and
     * the children models are used for further processing
     *
     * @param string $relation
     * @param array $models
     * @return array
     */
    protected function getRelationModels(string $relation, array $models)
    {
        $model = $models[0];
        $relationModels = defined("$model::CHILDREN") && isset(($model::CHILDREN)[$relation])
            ? ($model::CHILDREN)[$relation] : [];
        return is_array($relationModels) ? $relationModels: [$relationModels];
    }

    /**
     * @param array $relationWhere
     * @param array $relationModels
     * @param string $relation
     * @throws \Exception
     */
    protected function checkRelationDefined(array $relationWhere, array $relationModels, string $relation)
    {
        if (!empty($relationWhere) && empty($relationModels)) {
            throw new \Exception("Relation $relation not defined");
        }
    }

    /**
     * If strict is mentioned whereHas or whereDoesntHave is called
     * If strict is not mentioned with or load is called
     *
     * @param $resources
     * @param string $relation
     * @param array $relationWhere
     * @param string $childRelations
     * @param array $relationParameters
     * @param array $relationModels
     * @param string $relationKey
     * @param bool $strictMode
     * @return mixed
     */
    protected function dynamicNestedConstraintLoading(
        $resources,
        string $relation,
        array $relationWhere,
        string $childRelations,
        array $relationParameters,
        array $relationModels,
        string $relationKey,
        $strictMode = false
    ) {
        if ($strictMode) {
            $resources = $this->hasRelations(
                $resources,
                $relation,
                $relationWhere,
                $childRelations,
                $relationParameters,
                $relationModels,
                $relationKey,
                $strictMode
            );
        } else {
            $resources = $this->withRelations(
                $resources,
                $relation,
                $relationWhere,
                $childRelations,
                $relationParameters,
                $relationModels,
                $relationKey,
                $strictMode
            );
        }

        return $resources;
    }

    /**
     * Eloquent Querying Relationship Existence
     * Eloquent Querying Relationship Absence
     *
     * @param $resources
     * @param string $relation
     * @param array $relationWhere
     * @param string $childRelations
     * @param array $relationParameters
     * @param array $relationModels
     * @param string $relationKey
     * @param string $strictMode
     * @return mixed
     */
    protected function hasRelations(
        $resources,
        string $relation,
        array $relationWhere,
        string $childRelations,
        array $relationParameters,
        array $relationModels,
        string $relationKey,
        string $strictMode
    ) {
        $function = $strictMode == 'inverse' ? 'whereDoesntHave': 'whereHas';
        $resources->$function(
            $relation,
            function (Builder $query) use (
                $relationWhere,
                $childRelations,
                $relationParameters,
                $relationModels,
                $relationKey,
                $strictMode
            ) {
                if ($childRelations) {
                    $query = ($this->processDynamicNestedConstraintLoading(
                        $query,
                        $childRelations,
                        $relationParameters,
                        $relationModels,
                        $strictMode,
                        $relationKey
                    ));
                }
                $query = $this->queryConstraints($query, $relationWhere, $relationModels);
            }
        );
        return $resources;
    }

    /**
     * Nested Eager Loading
     * "." separated are child relations
     *
     * @param $resources
     * @param string $relation
     * @param array $relationWhere
     * @param string $childRelations
     * @param array $relationParameters
     * @param array $relationModels
     * @param string $relationKey
     * @param bool $strictMode
     * @return mixed
     */
    protected function withRelations(
        $resources,
        string $relation,
        array $relationWhere,
        string $childRelations,
        array $relationParameters,
        array $relationModels,
        string $relationKey,
        $strictMode = false
    ) {
        $function = $this->getRelationType();
        $resources->$function([
            $relation => function ($query) use (
                $relationWhere,
                $childRelations,
                $relationParameters,
                $relationModels,
                $relationKey,
                $strictMode
            ) {
                if ($childRelations) {
                    $query = ($this->processDynamicNestedConstraintLoading(
                        $query,
                        $childRelations,
                        $relationParameters,
                        $relationModels,
                        $strictMode,
                        $relationKey
                    ));
                }
                $query = $this->queryConstraints($query, $relationWhere, $relationModels);
            }
        ]);
        return $resources;
    }

    /**
     * @param Builder $resources
     * @return Builder
     */
    protected function addSortByToSearch(Builder $resources)
    {
        return $resources->orderBy(
            $this->request->get('sortBy') ?? 'id',
            $this->request->get('sortOrder') ?? 'asc'
        );
    }

    /**
     * A GET route already has resource called
     * Hence, its first call will be "load" and the next "with"
     *
     * @return string
     */
    protected function getRelationType(): string
    {
        if ($this->route == 'get') {
            $type = $this->loadCount == 0 ? 'load': 'with';
            $this->loadCount++;
        }
        return $type ?? 'with';
    }
}
