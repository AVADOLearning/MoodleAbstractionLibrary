<?php

namespace Avado\MoodleAbstractionLibrary\Routing\Controller;

use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\AbstractTagAwareAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Monolog\Logger;

/**
 * Class Controller
 * @package Avado\MoodleAbstractionLibrary\Routing\Controller
 */
abstract class Controller
{
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

        Paginator::currentPathResolver(function() use ($request) {
            $path = str_replace('?page='.$this->getPage(),'', $request->getUri());
            $path = str_replace('&page='.$this->getPage(),'', $path);

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
     * @return Builder
     */
    public function search()
    {
        $resources = (static::MODEL)::query();

        $queryParameters = $this->stripPaginationFields($this->request->query->all());

        foreach ($queryParameters as $key => $value) {
            if($condition = $this->pullOperatorFromParameterKey($key)){
                $resources->where(...$condition);
            } else {
                if(!$this->fieldIsSearchable($key)){
                    throw new \Exception("Field is not searchable");
                }
                $resources->where($key, $value);
            }
        }
        $resources = $this->addRelationshipsToSearch($resources, $this->request->get('strict'));
        $resources = $this->addOrderByToSearch($resources);

        return $resources;
    }

    /**
     * @param Builder $resources
     * @param string $strictMode
     * @return Builder
     */
    protected function addRelationshipsToSearch($resources, $strictMode = false)
    {
        if($relationships = $this->request->get('relationships')){
            if($strictMode === 'true'){
                foreach (explode(',', $relationships) as $relationship) {
                    $resources->has($relationship);
                }
            }else if($strictMode === 'inverse'){
                foreach (explode(',', $relationships) as $relationship) {
                    $resources->doesntHave($relationship);
                }
            }
            $resources->with(...explode(',', $relationships));
        }
        return $resources;
    }

    /**
     *
     * @param string $key
     * @return string|boolean
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
                $condition = explode($operator,$key);

                if(!$this->fieldIsSearchable($condition[0])){
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
        $paginationFields = ['page','offset','limit','relationships','strict', 'sortAsc', 'sortDesc'];

        return array_diff_key($queryParameters, array_flip($paginationFields));
    }

    /**
     * @param Builder $resources
     * @return Builder
     */
    protected function addOrderByToSearch(Builder $resources): Builder
    {
        $searchParams = preg_grep('/sortAsc|sortDesc/', array_keys($this->request->query->all()));

        foreach ($searchParams as $searchParam) {
            $orderBy = $searchParam == 'sortAsc' ? 'orderBy' : 'orderByDesc';

            foreach (explode(',', $this->request->get($searchParam)) as $sortable) {
                $resources->$orderBy($sortable);
            }
        }

        return $resources;
    }
}
