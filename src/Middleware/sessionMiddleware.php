<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

function sessionMiddleware(Request $request, RequestHandler $handler): Response
{
    if (!isset($_SESSION['token'])) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }

    return $handler->handle($request);
}
