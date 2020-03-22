<?php

namespace Avado\MoodleAbstractionLibrary\Controllers;

use Avado\MoodleAbstractionLibrary\Middleware\AuthMiddleware;
use Avado\MoodleAbstractionLibrary\Policies\AuthPolicy;
use Avado\MoodleAbstractionLibrary\Entities\User;
use Avado\MoodleAbstractionLibrary\Routing\Controller\Controller;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthController
 * @package Avado\MoodleAbstractionLibrary\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var string
     */
    const MODEL = User::class;

    /**
     * @var string
     */
    const POLICY = AuthPolicy::class;

    /**
     * @var string
     */
    const RESOURCE = 'users';

    /**
     * @Route("/auth", methods={"POST"})
     */
    public function auth()
    {
        $contents = json_decode($this->request->getContent());
        $username = $contents->data->username;
        $password = $contents->data->password;

        $user = User::where('username', $username)->first();

        if($this->verifyPassword($user, $password)){
            return new JsonResponse([
                'success'=>'true',
                'token' => JWT::encode($this->buildPayload($user), AuthMiddleware::JWT_KEY)
            ]);
        }
        return new JsonResponse(['success'=>'false', 'message'=>'Incorrect user details supplied.']);
    }

    /**
     * @param User $user
     * @return array
     */
    public function buildPayload(User $user)
    {
        return [
            'user'=> [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname
            ],
            'courses' => $user->enrolments()->pluck('id')
        ];
    }

    /**
     * @param $user
     * @param $password
     * @return bool
     */
    public function verifyPassword($user, $password)
    {
        return password_verify($password, $user->password);
    }
}
