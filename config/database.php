<?php
$host = $_ENV['DB_HOST'] ?? 'localhost';  // 環境変数からデータベースホストを取得
$db   = $_ENV['DB_NAME'] ?? 'your_database';  // データベース名
$user = $_ENV['DB_USER'] ?? 'your_username';  // データベースユーザー名
$pass = $_ENV['DB_PASS'] ?? 'your_password';  // データベースパスワード
$charset = 'utf8mb4';  // 文字セット

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";  // DSN（データソースネーム）

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // エラーモードを例外に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // デフォルトのフェッチモード
    PDO::ATTR_EMULATE_PREPARES   => false,  // エミュレートされた準備済みステートメントを無効化
];

try {
    // PDOインスタンスを生成してデータベースに接続
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // 接続エラーの場合に例外を投げる
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

return $pdo;  // PDOインスタンスを返す