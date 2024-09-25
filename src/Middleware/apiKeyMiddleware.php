<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

function apiKeyMiddleware(Request $request, RequestHandler $handler): Response
{
    // 設定したAPIキー（通常は環境変数などに保存します）
    $validApiKey = $_ENV['API_KEY'];  // 本番環境では.envファイルや環境変数に保存する

    // リクエストヘッダーからAPIキーを取得
    $apiKey = $request->getHeaderLine('X-API-Key');

    // APIキーが正しいかを確認
    if ($apiKey !== $validApiKey) {
        // 不正なAPIキーの場合、403 Forbidden を返す
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(403);
    }

    // APIキーが正しい場合は、次の処理に進む
    return $handler->handle($request);
}
