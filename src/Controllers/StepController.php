<?php

namespace Installer\Controllers;

use Installer\Core\Installer;
use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\ConfigWriter;
use Installer\Core\AdminCreator;
use Installer\Core\Utils;

class StepController
{
    private $installer;

    public function __construct(Installer $installer)
    {
        $this->installer = $installer;
    }

    public function showStep()
    {
        $step = $this->installer->getCurrentStep();
        $this->debug('Showing step: ' . $step);
        $data = [];

        // Handle step-specific logic and data loading
        switch ($step) {
            case 'welcome':
                // No specific data needed for welcome
                break;
            case 'license':
                // No specific data needed for license
                break;
            case 'system_check':
                $systemChecker = new SystemChecker();
                $systemChecker->checkSystem();
                $data['requirements'] = $systemChecker->getRequirements();
                $data['errors'] = $systemChecker->getErrors();
                break;
            case 'db_config':
                // Pre-fill with session data if available
                $config = include Utils::getBasePath('config/installer.php');
                $data['supported_databases'] = $config['supported_databases'] ?? [];
                $data['db_driver'] = $_SESSION['db_driver'] ?? 'mysql';
                $data['db_host'] = $_SESSION['db_host'] ?? 'localhost';
                $data['db_port'] = $_SESSION['db_port'] ?? '3306';
                $data['db_name'] = $_SESSION['db_name'] ?? '';
                $data['db_username'] = $_SESSION['db_username'] ?? 'root';
                $data['db_password'] = $_SESSION['db_password'] ?? '';
                break;
            case 'db_import':
                // No specific data needed, handled by POST
                break;
            case 'app_config':
                $data['app_name'] = $_SESSION['app_name'] ?? 'My Application';
                $data['app_url'] = $_SESSION['app_url'] ?? Utils::getBasePath();
                break;
            case 'admin_account':
                $data['admin_username'] = $_SESSION['admin_username'] ?? 'admin';
                $data['admin_email'] = $_SESSION['admin_email'] ?? '';
                break;
            case 'finish':
                // No specific data needed
                break;
            default:
                // Redirect to welcome or show error
                Utils::redirect(Utils::getBasePath());
                break;
        }

        $this->renderView($step, $data);
    }

    public function postStep()
    {
        $step = $this->installer->getCurrentStep();
        $this->debug('Processing POST for step: ' . $step);
        $errors = [];

        if (!Utils::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $errors[] = "Invalid CSRF token.";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                Utils::setAlert('danger', $error);
            }
            $this->showStep(); // Re-render current step with errors
            return;
        }

        switch ($step) {
            case 'welcome':
                // No specific POST data to process, just move to next step
                $this->installer->setNextStep();
                break;
            case 'license':
                if (empty($_POST['agree_license'])) {
                    Utils::setAlert('danger', 'You must agree to the license terms to continue.');
                    $this->showStep();
                    return;
                }
                $this->installer->setNextStep();
                break;
            case 'system_check':
                $systemChecker = new SystemChecker();
                if (!$systemChecker->checkSystem()) {
                    foreach ($systemChecker->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }
                $this->installer->setNextStep();
                break;
            case 'db_config':
                $dbDriver = Utils::sanitizeInput($_POST['db_driver'] ?? 'mysql');
                $dbHost = Utils::sanitizeInput($_POST['db_host'] ?? '');
                $dbPort = Utils::sanitizeInput($_POST['db_port'] ?? '');
                $dbName = Utils::sanitizeInput($_POST['db_name'] ?? '');
                $dbUsername = Utils::sanitizeInput($_POST['db_username'] ?? '');
                $dbPassword = $_POST['db_password'] ?? ''; // Don't sanitize password

                // Validate required fields based on driver
                if ($dbDriver === 'sqlite') {
                    if (empty($dbName)) {
                        Utils::setAlert('danger', 'Database file path is required for SQLite.');
                        $this->showStep();
                        return;
                    }
                } else {
                    if (empty($dbHost) || empty($dbPort) || empty($dbName) || empty($dbUsername)) {
                        Utils::setAlert('danger', 'All database fields are required.');
                        $this->showStep();
                        return;
                    }
                }

                $_SESSION['db_driver'] = $dbDriver;
                $_SESSION['db_host'] = $dbHost;
                $_SESSION['db_port'] = $dbPort;
                $_SESSION['db_name'] = $dbName;
                $_SESSION['db_username'] = $dbUsername;
                $_SESSION['db_password'] = $dbPassword;

                $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword, $dbDriver);
                if (!$dbManager->testConnection()) {
                    foreach ($dbManager->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }

                if (!$dbManager->createDatabase()) {
                    foreach ($dbManager->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }

                $this->installer->setNextStep();
                break;
            case 'db_import':
                $dbHost = $_SESSION['db_host'] ?? '';
                $dbPort = $_SESSION['db_port'] ?? '';
                $dbName = $_SESSION['db_name'] ?? '';
                $dbUsername = $_SESSION['db_username'] ?? '';
                $dbPassword = $_SESSION['db_password'] ?? '';
                $dbDriver = $_SESSION['db_driver'] ?? 'mysql';

                // Validate required fields based on driver
                if ($dbDriver === 'sqlite') {
                    if (empty($dbName)) {
                        Utils::setAlert('danger', 'Database file path is missing. Please go back to database configuration.');
                        $this->showStep();
                        return;
                    }
                } else {
                    if (empty($dbHost) || empty($dbName) || empty($dbUsername)) {
                        Utils::setAlert('danger', 'Database connection information is missing. Please go back to database configuration.');
                        $this->showStep();
                        return;
                    }
                }

                $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword, $dbDriver);
                
                $importType = $_POST['import_type'] ?? 'default';
                $config = include Utils::getBasePath('config/installer.php');
                
                if ($importType === 'migrations' && !empty($config['migration_support'])) {
                    $migrationPath = $config['migration_path'] ?? Utils::getBasePath('database/migrations');
                    if (!$dbManager->runMigrations($migrationPath)) {
                        foreach ($dbManager->getErrors() as $error) {
                            Utils::setAlert('danger', $error);
                        }
                        $this->showStep();
                        return;
                    }
                    Utils::setAlert('success', 'Database migrations completed successfully!');
                } else {
                    $sqlFilePath = $config['database_file'] ?? Utils::getBasePath('database/db.sql');
                    
                    if ($importType === 'upload') {
                        if (!isset($_FILES['sql_file']) || $_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
                            Utils::setAlert('danger', 'Please select a valid file to upload.');
                            $this->showStep();
                            return;
                        }
                        
                        $uploadedFile = $_FILES['sql_file']['tmp_name'];
                        $fileName = $_FILES['sql_file']['name'];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        
                        if ($fileExt === 'zip') {
                            $sqlFilePath = $this->extractZipFile($uploadedFile, $fileName);
                            if (!$sqlFilePath) {
                                $this->showStep();
                                return;
                            }
                        } elseif ($fileExt === 'sql') {
                            $sqlFilePath = $uploadedFile;
                        } else {
                            Utils::setAlert('danger', 'Please upload a valid .sql or .zip file.');
                            $this->showStep();
                            return;
                        }
                    } else {
                        // Check if default SQL file exists
                        if (!file_exists($sqlFilePath)) {
                            Utils::setAlert('danger', 'Default database schema file not found. Please upload a custom SQL file or use migrations.');
                            $this->showStep();
                            return;
                        }
                    }

                    if (!$dbManager->importSqlFile($sqlFilePath)) {
                        foreach ($dbManager->getErrors() as $error) {
                            Utils::setAlert('danger', $error);
                        }
                        $this->showStep();
                        return;
                    }
                    Utils::setAlert('success', "Database '{$dbName}' imported successfully!");
                }
                
                $this->installer->setNextStep();
                break;
            case 'app_config':
                $appName = Utils::sanitizeInput($_POST['app_name'] ?? '');
                $appUrl = Utils::sanitizeInput($_POST['app_url'] ?? '');

                if (empty($appName) || empty($appUrl)) {
                    Utils::setAlert('danger', 'Application name and URL are required.');
                    $this->showStep();
                    return;
                }

                $_SESSION['app_name'] = $appName;
                $_SESSION['app_url'] = $appUrl;

                $configWriter = new ConfigWriter();
                $envData = [
                    'APP_NAME' => $appName,
                    'APP_URL' => $appUrl,
                    'DB_HOST' => $_SESSION['db_host'],
                    'DB_PORT' => $_SESSION['db_port'],
                    'DB_DATABASE' => $_SESSION['db_name'],
                    'DB_USERNAME' => $_SESSION['db_username'],
                    'DB_PASSWORD' => $_SESSION['db_password'],
                    'APP_KEY' => Utils::generateRandomString(32)
                ];

                if (!$configWriter->writeEnvFile($envData)) {
                    foreach ($configWriter->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }

                $configData = [
                    'APP_NAME' => $appName,
                    'APP_URL' => $appUrl,
                    'INSTALLER_LOCK_ENABLED' => 'true'
                ];

                if (!$configWriter->writeConfigFile($configData)) {
                    foreach ($configWriter->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }

                Utils::setAlert('success', 'Application configuration saved!');
                $this->installer->setNextStep();
                break;
            case 'admin_account':
                $adminUsername = Utils::sanitizeInput($_POST['admin_username'] ?? '');
                $adminEmail = Utils::sanitizeInput($_POST['admin_email'] ?? '');
                $adminPassword = $_POST['admin_password'] ?? '';
                $adminPasswordConfirm = $_POST['admin_password_confirm'] ?? '';

                if (empty($adminUsername) || empty($adminEmail) || empty($adminPassword) || empty($adminPasswordConfirm)) {
                    Utils::setAlert('danger', 'All admin account fields are required.');
                    $this->showStep();
                    return;
                }

                if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    Utils::setAlert('danger', 'Invalid email format.');
                    $this->showStep();
                    return;
                }

                if ($adminPassword !== $adminPasswordConfirm) {
                    Utils::setAlert('danger', 'Passwords do not match.');
                    $this->showStep();
                    return;
                }

                $_SESSION['admin_username'] = $adminUsername;
                $_SESSION['admin_email'] = $adminEmail;

                $dbHost = $_SESSION['db_host'] ?? '';
                $dbPort = $_SESSION['db_port'] ?? '';
                $dbName = $_SESSION['db_name'] ?? '';
                $dbUsername = $_SESSION['db_username'] ?? '';
                $dbPassword = $_SESSION['db_password'] ?? '';

                try {
                    $dbDriver = $_SESSION['db_driver'] ?? 'mysql';
                    $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword, $dbDriver);
                    
                    // Build DSN based on driver
                    switch ($dbDriver) {
                        case 'mysql':
                            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName}";
                            break;
                        case 'pgsql':
                            $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
                            break;
                        case 'sqlite':
                            $dsn = "sqlite:{$dbName}";
                            break;
                        default:
                            throw new \InvalidArgumentException("Unsupported database driver: {$dbDriver}");
                    }
                    
                    $pdo = new \PDO($dsn, $dbUsername, $dbPassword);
                    $adminCreator = new AdminCreator($pdo);
                    if (!$adminCreator->createAdminUser($adminUsername, $adminEmail, $adminPassword)) {
                        foreach ($adminCreator->getErrors() as $error) {
                            Utils::setAlert('danger', $error);
                        }
                        $this->showStep();
                        return;
                    }
                    Utils::setAlert('success', 'Admin account created successfully!');
                    $this->installer->setNextStep();
                } catch (\PDOException $e) {
                    Utils::setAlert('danger', "Database error: " . $e->getMessage());
                    $this->showStep();
                    return;
                }
                break;
            case 'finish':
                $this->installer->createLockFile();
                session_destroy(); // Clear all session data
                Utils::setAlert('success', 'Installation completed successfully!');
                $this->installer->setNextStep(); // This will effectively do nothing as it's the last step
                break;
            default:
                Utils::redirect(Utils::getBasePath());
                break;
        }

        header('Location: index.php?step=' . $this->installer->getCurrentStep());
        exit;
    }

    private function renderView($step, $data = [])
    {
        $this->debug('Rendering view for step: ' . $step);
        extract($data); // Extract data to make it available in the view
        $installer = $this->installer; // Make installer object available in views
        $alerts = Utils::getAlerts(); // Get and clear alerts

        $this->debug('Including header.php');
        include Utils::getBasePath('src/Views/layouts/header.php');
        $this->debug('Including step view: ' . $step . '.php');
        include Utils::getBasePath("src/Views/steps/{$step}.php");
        $this->debug('Including footer.php');
        include Utils::getBasePath('src/Views/layouts/footer.php');
    }

    private function extractZipFile($zipPath, $fileName)
    {
        if (!class_exists('ZipArchive')) {
            Utils::setAlert('danger', 'ZIP extension not available. Please upload a .sql file instead.');
            return false;
        }
        
        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== TRUE) {
            Utils::setAlert('danger', 'Failed to open ZIP file: ' . $fileName);
            return false;
        }
        
        $extractPath = sys_get_temp_dir() . '/installer_' . uniqid();
        if (!mkdir($extractPath, 0755, true)) {
            Utils::setAlert('danger', 'Failed to create extraction directory.');
            $zip->close();
            return false;
        }
        
        if (!$zip->extractTo($extractPath)) {
            Utils::setAlert('danger', 'Failed to extract ZIP file.');
            $zip->close();
            return false;
        }
        
        $zip->close();
        
        // Find first .sql file in extracted contents
        $sqlFile = $this->findSqlFile($extractPath);
        if (!$sqlFile) {
            Utils::setAlert('danger', 'No .sql file found in ZIP archive.');
            $this->cleanupDirectory($extractPath);
            return false;
        }
        
        return $sqlFile;
    }
    
    private function findSqlFile($directory)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'sql') {
                return $file->getPathname();
            }
        }
        return false;
    }
    
    private function cleanupDirectory($directory)
    {
        if (is_dir($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']);
            foreach ($files as $file) {
                $path = $directory . '/' . $file;
                is_dir($path) ? $this->cleanupDirectory($path) : unlink($path);
            }
            rmdir($directory);
        }
    }

    private function debug($message)
    {
        if (isset($_GET['debug']) || defined('INSTALLER_DEBUG')) {
            echo "<div style='background:#f0f0f0;padding:5px;margin:2px;border-left:3px solid #007cba;font-family:monospace;font-size:12px;'>DEBUG: {$message}</div>";
        }
    }
}