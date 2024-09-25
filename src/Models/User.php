<?php

namespace App\Models;

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 全てのユーザーを取得
    public function getAllUsers()
    {
        $stmt = $this->pdo->query('SELECT id, name, email FROM users');
        return $stmt->fetchAll();
    }

    // 特定のユーザーをIDで取得
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // ユーザーを作成
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

    // ユーザーを更新
    public function updateUser($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        return $stmt->rowCount();
    }

    // ユーザーを削除
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
