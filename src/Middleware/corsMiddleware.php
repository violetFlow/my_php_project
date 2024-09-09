<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

function addCorsHeaders(Request $request, RequestHandler $handler): Response {
    // 次のミドルウェアまたはコントローラを処理
    $response = $handler->handle($request);
    
    // CORSヘッダーを追加
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*') // 必要に応じてドメインを変更
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
}