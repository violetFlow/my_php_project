<?php

// Controller
$userController = $container->get(App\Controllers\UserController::class);

// ルート定義
$app->get('/users', [$userController, 'index'])->add('apiKeyMiddleware');
$app->post('users', [$userController, 'create'])->add('apiKeyMiddleware');
