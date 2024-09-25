<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    private $pdo;

    // コンストラクタでPDOインスタンスを注入
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ユーザーの全リストを取得するメソッド
    public function getAllUsers()
    {
        $user = new User($this->pdo);
        return $user->getAllUsers();
    }

    // ユーザーを1人作成するメソッド
    public function createUser($data)
    {
        $user = new User($this->pdo);
        return $user->createUser($data);
    }

    /**
     * ログイン
     * @param $data ログインパラメータ(name, email, password)
     * @return $token トークン
     */
    public function login($data)
    {
        // Get datas
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();

        // Create token
        if (empty($user) || $user == null) {
            return null;
        }

        // Check password
        if (!password_verify($data['password'], $user['password'])) {
            return null;
        }

        $token = base64_encode($user['name'] . ':' . time());
        $_SESSION['token'] = $token;
        return $token;
    }

    /**
     * ログアウト
     */
    public function logout()
    {
        // セッションからトークンを削除
        unset($_SESSION['token']);
    }
}
