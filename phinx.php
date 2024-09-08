<?php

require __DIR__ . '/vendor/autoload.php';

// dotenv settings
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$host = $_ENV['DB_HOST_NOT_PORT'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => $host,
            'name' => $dbname,
            'user' => $username,
            'pass' => $password,
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
