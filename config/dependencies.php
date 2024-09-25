<?php
use DI\Container;
use App\Services\UserService;
use App\Controllers\UserController;
use App\Services\CommonService;
use App\Controllers\CommonController;


// コンテナのセットアップ
$container = new Container();

// PDOインスタンスをコンテナに登録
$container->set('pdo', function () {
    return require __DIR__ . '/database.php';
});

// Users
$container->set(UserController::class, function ($container) {
    return new UserController($container->get(UserService::class));
});
$container->set(UserService::class, function ($container) {
    return new UserService($container->get('pdo'));
});

// Common
$container->set(CommonController::class, function ($container) {
    return new CommonController($container->get(CommonService::class));
});
$container->set(CommonService::class, function ($container) {
    return new CommonService($container->get('pdo'));
});

return $container;
