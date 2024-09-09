<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;
use App\Validators\UserValidator;

class UserController {
    private $userService;

    // コンストラクタでUserServiceを注入
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    // 全てのユーザーを取得するアクション
    public function index(Request $request, Response $response, array $args): Response {
        $users = $this->userService->getAllUsers();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // ユーザーを1人作成するアクション
    public function create(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $errors = UserValidator::validate($data);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $userId = $this->userService->createUser($data);
        $response->getBody()->write(json_encode(['user_id' => $userId]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
