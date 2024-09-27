<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;
use App\Validators\UserValidator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="My First API",
 *     version="0.1"
 * )
 */
class UserController
{
    private $userService;

    // コンストラクタでUserServiceを注入
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/data.json",
     *     @OA\Response(
     *         response="200",
     *         description="The data"
     *     )
     * )
     */
    public function index(Response $response): Response
    {
        $users = $this->userService->getAllUsers();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * ユーザーを１人取得するアクション
     */
    public function readOne(Response $response, array $args): Response
    {
        $id = $args['id'];
        $errors = UserValidator::readOneValidate($id);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $user = $this->userService->getUserById($id);
        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * ユーザーを1人作成するアクション
     */
    public function create(Request $request, Response $response): Response
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

    /**
     * ユーザーを1人作成するアクション
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $errors = UserValidator::updateValidate($id, $data);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $rowCount = $this->userService->updateUser($id, $data);

        $response->getBody()->write(json_encode(['row_count' => $rowCount]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * ユーザーを1人削除するアクション
     */
    public function delete(Response $response, array $args): Response
    {
        $id = $args['id'];
        $errors = UserValidator::deleteValidate($id);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $rowCount = $this->userService->deleteUser($id);
        $response->getBody()->write(json_encode(['row_count' => $rowCount]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * ログイン
     */
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

    /**
     * ログアウト
     */
    public function logout(Response $response): Response
    {
        $this->userService->logout();

        $response->getBody()->write(json_encode(['message' => 'Logged out successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
