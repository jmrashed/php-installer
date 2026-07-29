<?php

namespace Installer\Core;

use PDO;
use PDOException;
use Installer\Core\Debug;

class DatabaseManager
{
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;
    private $driver;
    private $pdo;
    private $errors = [];

    public function __construct($host = null, $port = null, $database = null, $username = null, $password = null, $driver = 'mysql')
    {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->driver = $driver;
    }

    public function setCredentials($host, $port, $database, $username, $password, $driver = 'mysql')
    {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->driver = $driver;
    }

    public function testConnection()
    {
        try {
            $dsn = $this->buildDsn(false);
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
        Debug::log("createDatabase() called for driver: {$this->driver}");

        if ($this->driver === 'sqlite') {
            Debug::log("SQLite database creation");
            // SQLite database is a file, created on connection if not exists.
            // We can try to touch the file to ensure the directory is writable.
            try {
                if (!file_exists($this->database)) {
                    touch($this->database);
                }
                return true;
            } catch (\Exception $e) {
                $this->errors[] = "Failed to create SQLite database file: " . $e->getMessage();
                return false;
            }
        }

        if (!$this->pdo) {
            Debug::log("No PDO connection, testing connection");
            if (!$this->testConnection()) {
                $this->errors[] = "No active PDO connection. Call testConnection() first.";
                Debug::log("Connection test failed");
                return false;
            }
            Debug::log("Connection test passed");
        }

        try {
            // Check if database exists
            Debug::log("Checking if database exists");

            $queryData = $this->getCheckDatabaseExistsQuery();
            $stmt = $this->pdo->prepare($queryData['sql']);
            $stmt->execute($queryData['params']);
            $exists = (int) $stmt->fetchColumn();

            Debug::log("Database exists check result: {$exists}");

            if ($exists > 0) {
                Debug::log("Database already exists");
                return true;
            }

            Debug::log("Creating database");
            $sql = $this->getCreateDatabaseSql();
            Debug::log("Create SQL: {$sql}");
            $this->pdo->exec($sql);
            Debug::log("Database created successfully");
            return true;
        } catch (PDOException $e) {
            Debug::log("PDO Exception: " . $e->getMessage());
            // Ignore "database already exists" errors for pgsql
            if ($this->driver === 'pgsql' && strpos($e->getMessage(), 'already exists') !== false) {
                return true;
            }
            $this->errors[] = "Failed to create database '{$this->database}': " . $e->getMessage();
            return false;
        }
    }

    private function getCheckDatabaseExistsQuery()
    {
        switch ($this->driver) {
            case 'mysql':
                return [
                    'sql' => 'SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :database',
                    'params' => [':database' => $this->database]
                ];
            case 'pgsql':
                return [
                    'sql' => 'SELECT COUNT(*) FROM pg_database WHERE datname = :database',
                    'params' => [':database' => $this->database]
                ];
            default:
                throw new \InvalidArgumentException("Database existence check not supported for driver: {$this->driver}");
        }
    }


    public function importSqlFile($sqlFilePath)
    {
        // Force create log file immediately
        $logFile = getcwd() . '/storage/logs/db_import.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        file_put_contents($logFile, date('Y-m-d H:i:s') . ' - IMPORT STARTED' . PHP_EOL, FILE_APPEND);

        $this->log("Starting SQL import from: {$sqlFilePath}");

        if (!$this->database) {
            $this->errors[] = "Database name not set. Cannot import SQL.";
            $this->log("ERROR: Database name not set");
            return false;
        }

        $this->log("Connecting to database: {$this->database}");
        try {
            $dsn = $this->buildDsn(true);
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->log("Database connection successful");

            // Drop all existing tables first
            $this->log("Dropping existing tables...");
            $this->dropAllTables();
            $this->log("Existing tables dropped");

            $this->log("Reading SQL file...");
            $sql = file_get_contents($sqlFilePath);
            if ($sql === false) {
                $this->errors[] = "Failed to read SQL file: {$sqlFilePath}";
                $this->log("ERROR: Failed to read SQL file");
                return false;
            }
            $this->log("SQL file read successfully. Size: " . strlen($sql) . " bytes");

            // Clean and prepare SQL
            $this->log("Cleaning SQL content...");
            $sql = $this->cleanSql($sql);
            $this->log("SQL content cleaned. New size: " . strlen($sql) . " bytes");

            // Split SQL into individual statements
            $statements = $this->splitStatements($sql);
            $this->log("Found " . count($statements) . " SQL statements");

            $executed = 0;
            foreach ($statements as $index => $statement) {
                if (!empty($statement) && !$this->isComment($statement)) {
                    $this->log("Executing statement " . ($index + 1) . ": " . substr($statement, 0, 50) . "...");
                    try {
                        $this->pdo->exec($statement);
                        $executed++;
                        $this->log("Statement " . ($index + 1) . " executed successfully");
                    } catch (PDOException $e) {
                        $error = "SQL Error: " . $e->getMessage() . " in statement: " . substr($statement, 0, 100) . "...";
                        $this->errors[] = $error;
                        $this->log("ERROR: " . $error);
                        return false;
                    }
                } else {
                    $this->log("Skipping comment/empty statement " . ($index + 1));
                }
            }

            $this->log("SQL import completed successfully. Executed {$executed} statements");
            return true;
        } catch (PDOException $e) {
            $error = "Failed to import SQL file: " . $e->getMessage();
            $this->errors[] = $error;
            $this->log("ERROR: " . $error);
            return false;
        }
    }

    private function dropAllTables()
    {
        try {
            $tables = [];

            switch ($this->driver) {
                case 'mysql':
                    $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
                    $this->log("Foreign key checks disabled (MySQL)");

                    $result = $this->pdo->query("SHOW TABLES");
                    while ($row = $result->fetch(PDO::FETCH_NUM)) {
                        $tables[] = $row[0];
                    }
                    break;

                case 'pgsql':
                    $result = $this->pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                    while ($row = $result->fetch(PDO::FETCH_NUM)) {
                        $tables[] = $row[0];
                    }
                    break;

                case 'sqlite':
                    $result = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    while ($row = $result->fetch(PDO::FETCH_NUM)) {
                        $tables[] = $row[0];
                    }
                    break;
            }

            $this->log("Found " . count($tables) . " existing tables to drop");

            foreach ($tables as $table) {
                switch ($this->driver) {
                    case 'mysql':
                        $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
                        break;
                    case 'pgsql':
                        $this->pdo->exec("DROP TABLE IF EXISTS \"{$table}\" CASCADE");
                        break;
                    case 'sqlite':
                        $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
                        break;
                }
                $this->log("Dropped table: {$table}");
            }

            if ($this->driver === 'mysql') {
                $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
                $this->log("Foreign key checks re-enabled (MySQL)");
            }
        } catch (PDOException $e) {
            $this->log("Warning during table drop: " . $e->getMessage());
        }
    }

    private function cleanSql($sql)
    {
        // Remove MySQL dump comments and settings that might cause issues
        $sql = preg_replace('/\/\*![0-9]+.*?\*\//s', '', $sql);
        $sql = preg_replace('/^--.*$/m', '', $sql);
        $sql = str_replace('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";', '', $sql);
        $sql = str_replace('SET time_zone = "+00:00";', '', $sql);

        // Remove incomplete INSERT statements that don't end properly
        $sql = preg_replace('/INSERT INTO[^;]*\([^)]*$/', '', $sql);

        // Clean up any trailing incomplete statements
        $sql = rtrim($sql, ", \n\r\t");

        return $sql;
    }

    private function isComment($statement)
    {
        $statement = trim($statement);
        return empty($statement) || strpos($statement, '--') === 0 || strpos($statement, '/*') === 0;
    }

    /**
     * Split a SQL dump into individual statements on `;`, without breaking on
     * semicolons that appear inside quoted strings or backtick-quoted
     * identifiers (e.g. INSERT INTO t (bio) VALUES ('Hello; World');). A plain
     * explode(';', $sql) corrupts any dump containing such values.
     */
    private function splitStatements($sql)
    {
        $statements = [];
        $buffer = '';
        $quoteChar = null;
        $escaped = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $buffer .= $char;

            if ($escaped) {
                $escaped = false;
                continue;
            }

            if ($quoteChar !== null) {
                if ($char === '\\' && $quoteChar !== '`') {
                    $escaped = true;
                } elseif ($char === $quoteChar) {
                    $quoteChar = null;
                }
                continue;
            }

            if ($char === "'" || $char === '"' || $char === '`') {
                $quoteChar = $char;
                continue;
            }

            if ($char === ';') {
                $statement = trim(substr($buffer, 0, -1));
                if ($statement !== '') {
                    $statements[] = $statement;
                }
                $buffer = '';
            }
        }

        $tail = trim($buffer);
        if ($tail !== '') {
            $statements[] = $tail;
        }

        return $statements;
    }

    private function log($message)
    {
        // Try multiple possible paths
        $possiblePaths = [
            defined('INSTALLER_BASE_PATH') ? INSTALLER_BASE_PATH . '/storage/logs/db_import.log' : null,
            __DIR__ . '/../../storage/logs/db_import.log',
            getcwd() . '/storage/logs/db_import.log',
            sys_get_temp_dir() . '/installer_db_import.log'
        ];

        $logFile = null;
        foreach ($possiblePaths as $path) {
            if ($path) {
                $logDir = dirname($path);
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }
                if (is_dir($logDir) && is_writable($logDir)) {
                    $logFile = $path;
                    break;
                }
            }
        }

        if ($logFile) {
            @file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        Debug::log("DB: {$message}");

        // Also try to write to simple log file
        $simpleLog = getcwd() . '/storage/logs/db_import.log';
        @file_put_contents($simpleLog, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    }

    private function buildDsn($includeDatabase = false)
    {
        switch ($this->driver) {
            case 'mysql':
                $dsn = "mysql:host={$this->host};port={$this->port}";
                if ($includeDatabase) {
                    $dsn .= ";dbname={$this->database}";
                }
                return $dsn;
            case 'pgsql':
                $dsn = "pgsql:host={$this->host};port={$this->port}";
                if ($includeDatabase) {
                    $dsn .= ";dbname={$this->database}";
                }
                return $dsn;
            case 'sqlite':
                return "sqlite:{$this->database}";
            default:
                throw new \InvalidArgumentException("Unsupported database driver: {$this->driver}");
        }
    }

    private function getCreateDatabaseSql()
    {
        switch ($this->driver) {
            case 'mysql':
                return "CREATE DATABASE IF NOT EXISTS `{$this->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
            case 'pgsql':
                return "CREATE DATABASE \"{$this->database}\" WITH ENCODING 'UTF8';";
            default:
                throw new \InvalidArgumentException("Database creation not supported for driver: {$this->driver}");
        }
    }

    public function runMigrations($migrationPath)
    {
        if (!is_dir($migrationPath)) {
            $this->errors[] = "Migration directory not found: {$migrationPath}";
            return false;
        }

        $this->log("Running PHP migrations from: {$migrationPath}");

        try {
            // Connect to the database
            $dsn = $this->buildDsn(true);
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Get all PHP migration files
            $migrations = glob($migrationPath . '/*.php');
            sort($migrations);

            $this->log("Found " . count($migrations) . " migration files");

            foreach ($migrations as $migration) {
                $migrationName = basename($migration);
                $this->log("Running migration: {$migrationName}");

                // Execute the migration function
                $migrationFn = require $migration;
                if (is_callable($migrationFn)) {
                    $migrationFn($pdo);
                    $this->log("Migration {$migrationName} completed successfully");
                } else {
                    $this->errors[] = "Migration {$migrationName} does not return a callable function";
                    return false;
                }
            }

            $this->log("All migrations completed successfully");
            return true;
        } catch (\Throwable $e) {
            $this->errors[] = "Migration error: " . $e->getMessage();
            $this->log("ERROR: Migration failed - " . $e->getMessage());
            return false;
        }
    }

    public function runSeeders($seederPath)
    {
        if (!is_dir($seederPath)) {
            $this->errors[] = "Seeder directory not found: {$seederPath}";
            return false;
        }

        $this->log("Running PHP seeders from: {$seederPath}");

        try {
            // Connect to the database
            $dsn = $this->buildDsn(true);
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Get all PHP seeder files
            $seeders = glob($seederPath . '/*.php');
            sort($seeders);

            $this->log("Found " . count($seeders) . " seeder files");

            foreach ($seeders as $seeder) {
                $seederName = basename($seeder);
                $this->log("Running seeder: {$seederName}");

                // Execute the seeder function
                $seederFn = require $seeder;
                if (is_callable($seederFn)) {
                    $seederFn($pdo);
                    $this->log("Seeder {$seederName} completed successfully");
                } else {
                    $this->errors[] = "Seeder {$seederName} does not return a callable function";
                    return false;
                }
            }

            $this->log("All seeders completed successfully");
            return true;
        } catch (\Throwable $e) {
            $this->errors[] = "Seeder error: " . $e->getMessage();
            $this->log("ERROR: Seeder failed - " . $e->getMessage());
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
