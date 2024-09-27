<?php
// Controller
$userController = $container->get(App\Controllers\UserController::class);
$commonController = $container->get(App\Controllers\CommonController::class);

// Common
$app->get('/hello/{name}', [$commonController, 'hello'])->add('apiKeyMiddleware');
$app->post('/encrypt', [$commonController, 'encrypt'])->add('apiKeyMiddleware');
$app->post('/decrypt', [$commonController, 'decrypt'])->add('apiKeyMiddleware');

$app->get('/users', [$userController, 'index'])->add('apiKeyMiddleware')->add('sessionMiddleware');

$app->get('/users/{userId}', [$userController, 'readOne'])->add('apiKeyMiddleware')->add('sessionMiddleware');
$app->post('/users', [$userController, 'create'])->add('apiKeyMiddleware');
$app->put('/users', [$userController, 'update'])->add('apiKeyMiddleware')->add('sessionMiddleware');
$app->delete('/users/{userId}', [$userController, 'delete'])->add('apiKeyMiddleware')->add('sessionMiddleware');
$app->post('/login', [$userController, 'login'])->add('apiKeyMiddleware');
$app->post('/logout', [$userController, 'logout'])->add('apiKeyMiddleware')->add('sessionMiddleware');
