<?php

namespace Avado\MoodleAbstractionLibrary\Routing\Controller;

use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

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
}
