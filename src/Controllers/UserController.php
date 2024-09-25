<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;
use App\Validators\UserValidator;

class UserController
{
    private $userService;

    // コンストラクタでUserServiceを注入
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // 全てのユーザーを取得するアクション
    public function index(Request $request, Response $response, array $args): Response
    {
        $users = $this->userService->getAllUsers();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // ユーザーを1人作成するアクション
    public function create(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $errors = UserValidator::createValidate($data);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $userId = $this->userService->createUser($data);
        $response->getBody()->write(json_encode(['user_id' => $userId]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // ログイン
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $errors = UserValidator::loginValidate($data);

        // Check validation result
        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $token = $this->userService->login($data);

        // Check token
        if ($token == null) {
            $response->getBody()->write(json_encode(['errors' => 'Invalid credentials. Please check your email or password']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['token' => $token]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    // ログアウト
    public function logout(Request $request, Response $response): Response
    {
        $this->userService->logout();

        $response->getBody()->write(json_encode(['message' => 'Logged out successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
