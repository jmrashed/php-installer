<?php

namespace Installer\Core;

class SystemChecker
{
    private $requirements = [];
    private $errors = [];

    public function __construct()
    {
        $this->loadRequirements();
    }

    private function loadRequirements()
    {
        // Define minimum PHP version
        $this->requirements['php_version'] = [
            'name' => 'PHP Version',
            'required' => '7.4.0',
            'current' => PHP_VERSION,
            'status' => false,
            'message' => ''
        ];

        // Load configuration to get supported databases
        $config = include Utils::getBasePath('config/installer.php');
        $supportedDatabases = $config['supported_databases'] ?? [];
        
        // Define required PHP extensions
        $this->requirements['extensions'] = [
            'pdo' => [
                'name' => 'PDO Extension',
                'status' => false,
                'message' => ''
            ],
            'json' => [
                'name' => 'JSON Extension',
                'status' => false,
                'message' => ''
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'status' => false,
                'message' => ''
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'status' => false,
                'message' => ''
            ],
            'curl' => [
                'name' => 'cURL Extension',
                'status' => false,
                'message' => ''
            ]
        ];
        
        // Add database-specific extensions
        foreach ($supportedDatabases as $driver => $dbConfig) {
            if (isset($dbConfig['extension'])) {
                $this->requirements['extensions'][$dbConfig['extension']] = [
                    'name' => $dbConfig['name'] . ' PDO Driver',
                    'status' => false,
                    'message' => '',
                    'optional' => true // Mark as optional since user can choose different DB
                ];
            }
        }

        // Define writable directories
        $this->requirements['writable_directories'] = [
            'storage/logs' => [
                'name' => 'storage/logs directory',
                'path' => Utils::getBasePath('storage/logs'),
                'status' => false,
                'message' => ''
            ],
            'storage/tmp' => [
                'name' => 'storage/tmp directory',
                'path' => Utils::getBasePath('storage/tmp'),
                'status' => false,
                'message' => ''
            ],
            'config' => [
                'name' => 'config directory',
                'path' => Utils::getBasePath('config'),
                'status' => false,
                'message' => ''
            ],
            '.env' => [
                'name' => '.env file',
                'path' => Utils::getBasePath('.env'),
                'status' => false,
                'message' => ''
            ]
        ];
    }

    public function checkSystem()
    {
        $this->checkPhpVersion();
        $this->checkExtensions();
        $this->checkWritableDirectories();

        return empty($this->errors);
    }

    private function checkPhpVersion()
    {
        $requiredVersion = $this->requirements['php_version']['required'];
        $currentVersion = $this->requirements['php_version']['current'];

        if (version_compare($currentVersion, $requiredVersion, '>=')) {
            $this->requirements['php_version']['status'] = true;
            $this->requirements['php_version']['message'] = 'OK';
        } else {
            $this->requirements['php_version']['status'] = false;
            $this->requirements['php_version']['message'] = "Required PHP version {$requiredVersion} or higher. Current version is {$currentVersion}.";
            $this->errors[] = $this->requirements['php_version']['message'];
        }
    }

    private function checkExtensions()
    {
        foreach ($this->requirements['extensions'] as $extName => &$ext) {
            if (extension_loaded($extName)) {
                $ext['status'] = true;
                $ext['message'] = 'Installed';
            } else {
                $ext['status'] = false;
                $isOptional = isset($ext['optional']) && $ext['optional'];
                $ext['message'] = ($isOptional ? "Optional extension" : "Required extension") . " '{$ext['name']}' is not installed.";
                
                // Only add to errors if it's not optional
                if (!$isOptional) {
                    $this->errors[] = $ext['message'];
                }
            }
        }
    }

    private function checkWritableDirectories()
    {
        foreach ($this->requirements['writable_directories'] as $dirKey => &$dir) {
            // Create directory if it doesn't exist
            if (!file_exists($dir['path'])) {
                if (!is_dir(dirname($dir['path']))) {
                    mkdir(dirname($dir['path']), 0755, true);
                }
                if (strpos($dir['path'], '.env') === false) {
                    mkdir($dir['path'], 0755, true);
                } else {
                    touch($dir['path']);
                }
            }
            
            if (is_writable($dir['path'])) {
                $dir['status'] = true;
                $dir['message'] = 'Writable';
            } else {
                $dir['status'] = false;
                $dir['message'] = "Directory '{$dir['name']}' is not writable. Please set appropriate permissions (e.g., chmod 775 or 777).";
                $this->errors[] = $dir['message'];
            }
        }
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}