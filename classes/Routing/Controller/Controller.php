<?php

namespace Avado\MoodleAbstractionLibrary\Routing\Controller;

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
}
