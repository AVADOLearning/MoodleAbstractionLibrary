<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/auth' => [[['_route' => 'avado_alpapi_auth_controllers_auth_auth', '_controller' => 'Avado\\AlpApi\\Auth\\Controllers\\AuthController::auth'], null, ['POST' => 0], null, false, false, null]],
        '/childcourses' => [
            [['_route' => 'avado_alpapi_courses_controllers_childcourseversion_getall', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ChildCourseVersionController::getAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_childcourseversion_create', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ChildCourseVersionController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_childcourseversion_update', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ChildCourseVersionController::update'], null, ['PATCH' => 0], null, false, false, null],
        ],
        '/coursecategories' => [
            [['_route' => 'avado_alpapi_courses_controllers_coursecategory_getall', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseCategoryController::getAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_coursecategory_create', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseCategoryController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_coursecategory_update', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseCategoryController::update'], null, ['PATCH' => 0], null, false, false, null],
        ],
        '/courses' => [
            [['_route' => 'avado_alpapi_courses_controllers_course_create', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_course_search', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseController::search'], null, ['GET' => 0], null, false, false, null],
        ],
        '/parentcourses/possibles' => [[['_route' => 'avado_alpapi_courses_controllers_parentcourseversion_future', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ParentCourseVersionController::future'], null, ['GET' => 0], null, false, false, null]],
        '/parentcourses' => [
            [['_route' => 'avado_alpapi_courses_controllers_parentcourseversion_getall', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ParentCourseVersionController::getAll'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_parentcourseversion_create', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ParentCourseVersionController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_courses_controllers_parentcourseversion_update', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ParentCourseVersionController::update'], null, ['PATCH' => 0], null, false, false, null],
        ],
        '/users' => [
            [['_route' => 'avado_alpapi_users_controllers_user_create', '_controller' => 'Avado\\AlpApi\\Users\\Controllers\\UserController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'avado_alpapi_users_controllers_user_search', '_controller' => 'Avado\\AlpApi\\Users\\Controllers\\UserController::search'], null, ['GET' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/c(?'
                    .'|o(?'
                        .'|horts/([^/]++)(?'
                            .'|(*:33)'
                            .'|/cohorts(*:48)'
                        .')'
                        .'|urse(?'
                            .'|categories/([^/]++)(*:82)'
                            .'|s/([^/]++)(?'
                                .'|(*:102)'
                            .')'
                        .')'
                    .')'
                    .'|hildcourses/([^/]++)(*:133)'
                .')'
                .'|/parentcourses/([^/]++)(*:165)'
                .'|/users/([^/]++)(?'
                    .'|(*:191)'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        33 => [[['_route' => 'avado_alpapi_cohorts_controllers_cohort_get', '_controller' => 'Avado\\AlpApi\\Cohorts\\Controllers\\CohortController::get'], ['id'], ['GET' => 0], null, false, true, null]],
        48 => [[['_route' => 'avado_alpapi_cohorts_controllers_cohort_getbycourseid', '_controller' => 'Avado\\AlpApi\\Cohorts\\Controllers\\CohortController::getByCourseId'], ['id'], ['GET' => 0], null, false, false, null]],
        82 => [[['_route' => 'avado_alpapi_courses_controllers_coursecategory_delete', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseCategoryController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        102 => [
            [['_route' => 'avado_alpapi_courses_controllers_course_get', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseController::get'], ['course'], ['GET' => 0], null, false, true, null],
            [['_route' => 'avado_alpapi_courses_controllers_course_update', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseController::update'], ['course'], ['PATCH' => 0], null, false, true, null],
            [['_route' => 'avado_alpapi_courses_controllers_course_delete', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\CourseController::delete'], ['course'], ['DELETE' => 0], null, false, true, null],
        ],
        133 => [[['_route' => 'avado_alpapi_courses_controllers_childcourseversion_delete', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ChildCourseVersionController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        165 => [[['_route' => 'avado_alpapi_courses_controllers_parentcourseversion_delete', '_controller' => 'Avado\\AlpApi\\Courses\\Controllers\\ParentCourseVersionController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        191 => [
            [['_route' => 'avado_alpapi_users_controllers_user_get', '_controller' => 'Avado\\AlpApi\\Users\\Controllers\\UserController::get'], ['user'], ['GET' => 0], null, false, true, null],
            [['_route' => 'avado_alpapi_users_controllers_user_update', '_controller' => 'Avado\\AlpApi\\Users\\Controllers\\UserController::update'], ['user'], ['PATCH' => 0], null, false, true, null],
            [['_route' => 'avado_alpapi_users_controllers_user_delete', '_controller' => 'Avado\\AlpApi\\Users\\Controllers\\UserController::delete'], ['user'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
