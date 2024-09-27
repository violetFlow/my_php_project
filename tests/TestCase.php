<?php

declare(strict_types=1);

namespace Tests;

use DI\ContainerBuilder;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

class TestCase extends PHPUnit_TestCase
{
    use ProphecyTrait;

    /**
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $container = require __DIR__ . '/../config/dependencies.php';

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

        // CORS
        require __DIR__ . '/../src/Middleware/corsMiddleware.php';
        $app->add('addCorsHeaders');

        // API KEY
        require __DIR__ . '/../src/Middleware/apiKeyMiddleware.php';

        // セッション
        ini_set('session.cookie_httponly', 1); // HttpOnly属性を追加
        ini_set('session.cookie_secure', 1);   // Secure属性を追加（HTTPS環境でのみ有効）
        ini_set('session.gc_maxlifetime', 1800); // 30分に設定
        ini_set('session.cookie_lifetime', 0);   // ブラウザを閉じるまでセッション有効
        session_start();
        require __DIR__ . '/../src/Middleware/sessionMiddleware.php';

        // Register middleware
        require __DIR__ . '/../src/Routes/routes.php';

        return $app;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $headers
     * @param array  $cookies
     * @param array  $serverParams
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}