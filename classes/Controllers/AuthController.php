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
                'accesstoken' => JWT::encode($this->buildAccessToken($user), AuthMiddleware::JWT_KEY),
                'refreshtoken' => JWT::encode($this->buildRefreshToken($user), AuthMiddleware::JWT_KEY)
            ]);
        }
        return new JsonResponse(['success'=>'false', 'message'=>'Incorrect user details supplied.']);
    }

    /**
     * @Route("/refresh", methods={"POST"})
     */
    public function refresh()
    {
        $refreshToken = $this->request->headers->get('refreshtoken');

        $user = $this->pullUserFromRefreshToken($refreshToken, $this->request->server->get('SERVER_NAME'));

        if($refreshToken && $user){
            return new JsonResponse([
                'success'=>'true',
                'accesstoken' => JWT::encode($this->buildAccessToken($user), AuthMiddleware::JWT_KEY),
                'refreshtoken' => JWT::encode($this->buildRefreshToken($user), AuthMiddleware::JWT_KEY)
            ]);
        }
        return new JsonResponse(['success'=>'false', 'message'=>'Invalid refresh token provided']);
    }

    /**
     * @param User $user
     * @return array
     */
    public function buildAccessToken(User $user)
    {
        return [
            'user'=> [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname
            ],
            'expiry' => time() + 900,
            'type' => 'accesstoken',
            'host' => $this->request->server->get('SERVER_NAME')
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function buildRefreshToken(User $user)
    {
        return [
            'userId' => $user->id,
            'expiry' => time() + 5184000,
            'type' => 'refreshtoken',
            'host' => $this->request->server->get('SERVER_NAME')
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

    /**
     * @param string $token
     * @return void
     */
    protected function pullUserFromRefreshToken($token, $host)
    {
        $token = JWT::decode($token, AuthMiddleware::JWT_KEY, [AuthMiddleware::ALGORITHM]);

        if($token->expiry > time() && $token->host == $host && $token->type == 'refreshtoken'){
            return User::find($token->userId);
        }
        return false;
    }
}
