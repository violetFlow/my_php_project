<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Respect\Validation\Validator as v;

require __DIR__ . '/../vendor/autoload.php';

// Application Class
/*
use App\Example;
use App\Encrypt;
use App\Decrypt;
*/

// dotenv settings
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Create Container using PHP-DI
$container = new Container();
AppFactory::setContainer($container);

$container->set('db', function () {
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $username, $password, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
});

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 *
 * PSR-7の主な目的は、異なるフレームワークやライブラリ間でHTTPメッセージを扱うときに、統一されたインターフェースを提供し、互換性と再利用性を向上させることです。
 */
$app = AppFactory::create();

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  *
  * ミドルウェアは、リクエストやレスポンスに対して追加の処理を行う小さなコンポーネントです。
  * ミドルウェアは、リクエストがルートに到達する前やレスポンスがクライアントに送信される前に実行されるため、例えば、認証、ロギング、CORSの設定、エラーハンドリングなどの処理を担当することができます。
  */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$encryptKey = $_ENV['ENCRYPT_KEY'];

$nameValidator = v::noWhitespace()->length(1, 15);
// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response, $args) use ($nameValidator) {
    $name = $args['name'];
    if (!$nameValidator->validate($name)) {
        $response->getBody()->write("401: Please Input correct name");
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }

    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/users', function (Request $request, Response $response, $args) {
    try {
        $db = $this->get('db');
        $stmt = $db->query('SELECT * FROM users');
        $users = $stmt->fetchAll();

        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
});

/*
$app->get('/encrypt/{encryptTxt}', function (Request $request, Response $response, $args) use ($encryptKey) {
    $encryptTxt = $args['encryptTxt'];

    // URL暗号化
    $encrypt = new Encrypt();
    $encryptedUrl = $encrypt->encrypt_url($encryptTxt, $encryptKey);
    $response->getBody()->write($encryptedUrl);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/decrypt/{encryptedUrl}', function (Request $request, Response $response, $args) use ($encryptKey) {
    $encryptedUrl = $args['encryptedUrl'];

    // URL複合化
    $decrypt = new Decrypt();
    $decryptedUrl = $decrypt->decrypt_url($encryptedUrl, $encryptKey);
    $response->getBody()->write($decryptedUrl);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/say-hello', function (Request $request, Response $response, $args) {
    $example = new Example();
    $response->getBody()->write($example->sayHello());
    return $response->withHeader('Content-Type', 'application/json');
});
*/

// Run app
$app->run();
