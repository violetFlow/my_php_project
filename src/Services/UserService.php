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

    /**
     * ユーザーを全リスト取得するメソッド
     * @return $users 全ユーザー情報
     */
    public function getAllUsers()
    {
        $user = new User($this->pdo);
        return $user->getAllUsers();
    }

    /**
     * ユーザーを1人取得するメソッド
     * @param $id ユーザーID
     * @return $user ユーザー情報
     */
    public function getUserById($id)
    {
        $user = new User($this->pdo);
        return $user->getUserById($id);
    }

    /**
     * ユーザーを1人作成するメソッド
     * @param $data ユーザー情報
     * @return $lastInsertId 最後に挿入されたユーザーID
     */
    public function createUser($data)
    {
        $user = new User($this->pdo);
        return $user->createUser($data);
    }

    /**
     * ユーザーを1人更新するメソッド
     * @param $id ユーザーID
     * @param $data ユーザー情報
     * @return $rowCount
     */
    public function updateUser($id, $data)
    {
        $user = new User($this->pdo);
        return $user->updateUser($id, $data);
    }

    /**
     * ユーザーを1人削除するメソッド
     * @param $id ユーザーID
     * @return $rowCount
     */
    public function deleteUser($id)
    {
        $user = new User($this->pdo);
        return $user->deleteUser($id);
    }

    /**
     * ログイン
     * @param $data ログイン情報(email, password)
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
