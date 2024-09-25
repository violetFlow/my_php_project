<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $adminPassword = $_ENV['ADMIN_PASSWORD'];
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

        // 挿入するデータ
        $data = [
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => $hashedPassword,
            ],
        ];

        // users テーブルにデータを挿入
        $users = $this->table('users');
        $users->insert($data)
              ->saveData();
    }
}
