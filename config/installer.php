<?php
return [
    'app_name' => 'Generic PHP App',
    'version' => '1.0.0',
    'php_version' => '8.1',
    'required_extensions' => ['pdo_mysql', 'curl', 'zip', 'mbstring'],
    'writable_dirs' => ['config', 'uploads', 'storage'],
    'database_file' => __DIR__ . '/../database/db.sql'
];
