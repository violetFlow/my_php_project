<?php
// dependencies.php

use DI\Container;
use App\Services\UserService;
use App\Controllers\UserController;

// コンテナのセットアップ
$container = new Container();

// PDOインスタンスをコンテナに登録
$container->set('pdo', function () {
    return require __DIR__ . '/database.php';
});

// UserServiceのインスタンスをコンテナに登録
$container->set(UserService::class, function ($container) {
    return new UserService($container->get('pdo'));
});

// UserControllerのインスタンスをコンテナに登録
$container->set(UserController::class, function ($container) {
    return new UserController($container->get(UserService::class));
});

return $container;
