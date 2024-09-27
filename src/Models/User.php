<?php

namespace App\Models;

class User
{
    private $pdo;

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
        $stmt = $this->pdo->query('SELECT id, name, email FROM users');
        return $stmt->fetchAll();
    }

    /**
     * ユーザーを1人取得するメソッド
     * @param $id ユーザーID
     * @return $user ユーザー情報
     */
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * ユーザーを1人作成するメソッド
     * @param $data ユーザー情報
     * @return $lastInsertId 最後に挿入されたユーザーID
     */
    public function createUser($data)
    {
        // Hashing password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * ユーザーを1人更新するメソッド
     * @param $id ユーザーID
     * @param $data ユーザー情報
     * @return $rowCount
     */
    public function updateUser($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        return $stmt->rowCount();
    }

    /**
     * ユーザーを1人削除するメソッド
     * @param $id ユーザーID
     * @return $rowCount
     */
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
