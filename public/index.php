<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 環境変数の読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// DIコンテナの読み込み
$container = require __DIR__ . '/../config/dependencies.php';
AppFactory::setContainer($container);


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

// cors
require __DIR__ . '/../src/Middleware/corsMiddleware.php';
$app->add('addCorsHeaders');

// API KEY
require __DIR__ . '/../src/Middleware/apiKeyMiddleware.php';

// ルート定義ファイルを読み込む
require __DIR__ . '/../src/Routes/routes.php';


// アプリケーションを実行
$app->run();