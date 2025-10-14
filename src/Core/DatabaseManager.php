<?php

namespace Installer\Core;

use PDO;
use PDOException;

class DatabaseManager
{
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;
    private $pdo;
    private $errors = [];

    public function __construct($host = null, $port = null, $database = null, $username = null, $password = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function setCredentials($host, $port, $database, $username, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function testConnection()
    {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            $this->errors[] = "Database connection failed: " . $e->getMessage();
            return false;
        }
    }

    public function createDatabase()
    {
        if (!$this->pdo) {
            $this->errors[] = "No active PDO connection. Call testConnection() first.";
            return false;
        }

        try {
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            return true;
        } catch (PDOException $e) {
            $this->errors[] = "Failed to create database '{$this->database}': " . $e->getMessage();
            return false;
        }
    }

    public function importSqlFile($sqlFilePath)
    {
        if (!$this->database) {
            $this->errors[] = "Database name not set. Cannot import SQL.";
            return false;
        }

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = file_get_contents($sqlFilePath);
            if ($sql === false) {
                $this->errors[] = "Failed to read SQL file: {$sqlFilePath}";
                return false;
            }

            // Execute SQL queries
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            $this->errors[] = "Failed to import SQL file: " . $e->getMessage();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}