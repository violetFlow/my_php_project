<?php

namespace App\Services;
use App\Models\User;

class UserService {
    private $pdo;

    // コンストラクタでPDOインスタンスを注入
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ユーザーの全リストを取得するメソッド
    public function getAllUsers() {
        $user = new User($this->pdo);
        return $user->getAllUsers();
    }

    // ユーザーを1人作成するメソッド
    public function createUser($data) {
        $user = new User($this->pdo);
        return $user->createUser($data);
    }
}