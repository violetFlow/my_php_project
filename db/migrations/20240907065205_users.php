<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Users extends AbstractMigration
{
    public function up()
    {
        // usersテーブルの作成
        $table = $this->table('users');
        $table->addColumn('name', 'string', ['limit' => 255, 'null' => false])
                ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
                ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
                ->addIndex(['email'], ['unique' => true])
                ->create();
    }

    public function down()
    {
        // usersテーブルの削除
        $this->table('users')->drop()->save();
    }
}
