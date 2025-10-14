<?php
/**
 * PHP Installer Configuration
 * 
 * This file contains the main configuration for the PHP installer.
 * Customize these settings according to your application's requirements.
 */
return [
    // Application Information
    'app_name' => 'Generic PHP App',
    'version' => '1.0.0',
    
    // System Requirements
    'php_version' => '8.1',
    'required_extensions' => ['pdo', 'curl', 'zip', 'mbstring'],
    'writable_dirs' => ['config', 'uploads', 'storage'],
    
    // Database Configuration
    'database_file' => __DIR__ . '/../database/db.sql',
    
    // Multi-Database Support
    // The installer supports multiple database types.
    // Users can select their preferred database during installation.
    'supported_databases' => [
        'mysql' => [
            'name' => 'MySQL',
            'extension' => 'pdo_mysql',
            'default_port' => 3306
        ],
        'pgsql' => [
            'name' => 'PostgreSQL',
            'extension' => 'pdo_pgsql',
            'default_port' => 5432
        ],
        'sqlite' => [
            'name' => 'SQLite',
            'extension' => 'pdo_sqlite',
            'default_port' => null
        ]
    ],
    
    // Migration System
    // Enable this to use database migrations instead of a single SQL file.
    // Migrations provide better version control and incremental updates.
    'migration_support' => true,
    'migration_path' => __DIR__ . '/../database/migrations'
];
