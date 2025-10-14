<?php

namespace Installer\Core;

use PDO;
use PDOException;

class AdminCreator
{
    private $pdo;
    private $errors = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createAdminUser($username, $email, $password)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Assuming a 'users' table with 'username', 'email', 'password' fields
            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, created_at, updated_at) VALUES (:username, :email, :password, NOW(), NOW())");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            $this->errors[] = "Failed to create admin user: " . $e->getMessage();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}