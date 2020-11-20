<?php

namespace Avado\MoodleAbstractionLibrary\Routing\Controller;

use Avado\MoodleAbstractionLibrary\Database\Builder;
use Avado\MoodleAbstractionLibrary\Services\QueryResourceService;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\AbstractTagAwareAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Monolog\Logger;
use Avado\MoodleAbstractionLibrary\Traits\ChecksUserIsPrivileged;

/**
 * Class Controller
 * @package Avado\MoodleAbstractionLibrary\Routing\Controller
 */
abstract class Controller
{
    use ChecksUserIsPrivileged;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     * @return bool
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return true;
    }

    /**
     *
     * @param AbstractTagAwareAdapter $cacheAdapter
     * @param array $options
     * @return void
     */
    public function setCacheAdapter(AbstractTagAwareAdapter $cacheAdapter, array $options)
    {
        $this->cache = new $cacheAdapter(...$options);
    }

    /**
     * @param Monolog\Logger $logger
     * @return void
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     */
    public function boot()
    {
        $request = $this->request;

        Paginator::currentPathResolver(function () use ($request) {
            $path = str_replace('?page='.$this->getPage(), '', $request->getUri());
            $path = str_replace('&page='.$this->getPage(), '', $path);

            return $path;
        });
    }

    /**
     * @return int
     */
    protected function getPage()
    {
        return $_GET['page'] ?? 1;
    }

    /**
     * @return Builder|mixed
     * @throws \Exception
     */
    public function search()
    {
        if ($this->checkIfSearchFieldsDefined()) {
            $queryResource = new QueryResourceService($this->request);
            return $queryResource->search((get_class($this))::MODEL);
        } else {
            (static::MODEL)::setPrivileged($this->isStaff($this->request));
            $resources = (static::MODEL)::query();

            $queryParameters = $this->stripPaginationFields($this->request->query->all());

            foreach ($queryParameters as $key => $value) {
                if ($condition = $this->pullOperatorFromParameterKey($key)) {
                    $resources->where(...$condition);
                } else {
                    if (!$this->fieldIsSearchable($key)) {
                        throw new \Exception("Field is not searchable");
                    }

                    if (strpos($value, ',') !== false) {
                        $resources->whereIn($key, explode(',', $value));
                    } else {
                        $resources->where($key, $value);
                    }
                }
            }
            $resources = $this->addRelationshipsToSearch($resources, $this->request->get('strict'));
            $resources = $this->addSortByToSearch($resources);

            return $resources;
        }
    }

    /**
     * @param Builder $resources
     * @param string $strictMode
     * @return Builder
     */
    protected function addRelationshipsToSearch($resources, $strictMode = false)
    {
        if ($relationships = $this->request->get('relationships')) {
            if ($strictMode === 'true') {
                foreach (explode(',', $relationships) as $relationship) {
                    $resources->has($relationship);
                }
            } elseif ($strictMode === 'inverse') {
                foreach (explode(',', $relationships) as $relationship) {
                    $resources->doesntHave($relationship);
                }
            }
            $resources->with(...explode(',', $relationships));
        }
        return $resources;
    }

    /**
     * @param string $key
     * @return array|bool
     * @throws \Exception
     */
    protected function pullOperatorFromParameterKey(string $key)
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

                if (!$this->fieldIsSearchable($condition[0])) {
                    throw new \Exception("Field is not searchable");
                }
                return [$condition[0],$operator,$condition[1]];
            }
        }
        return false;
    }

    protected function fieldIsSearchable($field)
    {
        return in_array($field, static::SEARCH_FIELDS);
    }

    /**
     * @param array $queryParameters
     * @return array
     */
    protected function stripPaginationFields($queryParameters)
    {
        $paginationFields = ['page','offset','limit','relationships','strict', 'sortBy', 'sortOrder'];

        return array_diff_key($queryParameters, array_flip($paginationFields));
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
     * @return bool
     */
    protected function checkIfSearchFieldsDefined(): bool
    {
        $model = ((get_class($this))::MODEL);
        return defined("$model::SEARCH_FIELDS");
    }
}
