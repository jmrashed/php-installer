<?php

namespace Installer\Controllers;

use Installer\Core\Installer;
use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\ConfigWriter;
use Installer\Core\AdminCreator;
use Installer\Core\Utils;
use Installer\Core\Debug;

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
                Debug::log("Loading db_config step");
                // Pre-fill with session data if available
                $configPath = Utils::getBasePath('config/installer.php');
                Debug::log("Config path: $configPath");
                
                if (file_exists($configPath)) {
                    Debug::log("Config file exists");
                    $config = include $configPath;
                    Debug::log("Config loaded: " . print_r($config, true));
                } else {
                    Debug::log("Config file not found, using defaults");
                    $config = [];
                }
                
                $data['supported_databases'] = $config['supported_databases'] ?? [
                    'mysql' => ['name' => 'MySQL', 'default_port' => '3306'],
                    'pgsql' => ['name' => 'PostgreSQL', 'default_port' => '5432'],
                    'sqlite' => ['name' => 'SQLite', 'default_port' => null]
                ];
                $data['db_driver'] = $_SESSION['db_driver'] ?? 'mysql';
                $data['db_host'] = $_SESSION['db_host'] ?? 'localhost';
                $data['db_port'] = $_SESSION['db_port'] ?? '3306';
                $data['db_name'] = $_SESSION['db_name'] ?? '';
                $data['db_username'] = $_SESSION['db_username'] ?? 'root';
                $data['db_password'] = $_SESSION['db_password'] ?? '';
                
                Debug::log("Data prepared for db_config: " . print_r($data, true));
                break;
            case 'db_import':
                // No specific data needed, handled by POST
                break;
            case 'app_config':
                $data['app_name'] = $_SESSION['app_name'] ?? 'Flat Management Software';
                // Generate proper base URL from current request
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $defaultUrl = $protocol . '://' . $host . '/';
                $data['app_url'] = $_SESSION['app_url'] ?? $defaultUrl;
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
        Debug::log("Processing POST for step: $step");
        Debug::log("POST data: " . print_r($_POST, true));
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
                Debug::log("Processing db_config POST");
                $dbDriver = Utils::sanitizeInput($_POST['db_driver'] ?? 'mysql');
                $dbHost = Utils::sanitizeInput($_POST['db_host'] ?? '');
                $dbPort = Utils::sanitizeInput($_POST['db_port'] ?? '');
                $dbName = Utils::sanitizeInput($_POST['db_name'] ?? '');
                $dbUsername = Utils::sanitizeInput($_POST['db_username'] ?? '');
                $dbPassword = $_POST['db_password'] ?? ''; // Don't sanitize password
                
                Debug::log("DB Config - Driver: $dbDriver, Host: $dbHost, Port: $dbPort, Name: $dbName, User: $dbUsername");

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

                Debug::log("Creating DatabaseManager");
                try {
                    $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword, $dbDriver);
                    Debug::log("DatabaseManager created, testing connection");
                    
                    if (!$dbManager->testConnection()) {
                        Debug::log("Connection test failed");
                        foreach ($dbManager->getErrors() as $error) {
                            Utils::setAlert('danger', $error);
                        }
                        $this->showStep();
                        return;
                    }
                    Debug::log("Connection test passed");
                } catch (Exception $e) {
                    echo "[ERROR] DatabaseManager error: " . $e->getMessage() . "<br>";
                    Utils::setAlert('danger', 'Database error: ' . $e->getMessage());
                    $this->showStep();
                    return;
                }

                Debug::log("Creating database");
                if (!$dbManager->createDatabase()) {
                    Debug::log("Database creation failed");
                    foreach ($dbManager->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }
                Debug::log("Database created successfully");

                Debug::log("Setting next step");
                $this->installer->setNextStep();
                echo "[DEBUG] Next step set, current step now: " . $this->installer->getCurrentStep() . "<br>";
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
                    Debug::log("Running migrations");
                    $migrationPath = $config['migration_path'] ?? Utils::getBasePath('database/migrations');
                    Debug::log("Migration path: $migrationPath");
                    
                    if (!$dbManager->runMigrations($migrationPath)) {
                        foreach ($dbManager->getErrors() as $error) {
                            Utils::setAlert('danger', $error);
                        }
                        $this->showStep();
                        return;
                    }
                    
                    // Run seeders if available
                    if (!empty($config['seeder_path']) && is_dir($config['seeder_path'])) {
                        Debug::log("Running seeders");
                        if (!$dbManager->runSeeders($config['seeder_path'])) {
                            foreach ($dbManager->getErrors() as $error) {
                                Utils::setAlert('danger', $error);
                            }
                            $this->showStep();
                            return;
                        }
                        Utils::setAlert('success', 'Database migrations and seeders completed successfully!');
                    } else {
                        Utils::setAlert('success', 'Database migrations completed successfully!');
                    }
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

                // Create the main application config file
                $configContent = "<?php\n";
                $configContent .= "// Database Configuration\n";
                $configContent .= "define('DB_HOST', '" . $_SESSION['db_host'] . "');\n";
                $configContent .= "define('DB_NAME', '" . $_SESSION['db_name'] . "');\n";
                $configContent .= "define('DB_USER', '" . $_SESSION['db_username'] . "');\n";
                $configContent .= "define('DB_PASS', '" . $_SESSION['db_password'] . "');\n\n";
                $configContent .= "// Application Configuration\n";
                $configContent .= "define('SITE_NAME', '" . $appName . "');\n";
                $configContent .= "define('DEBUG_MODE', false);\n";
                $configContent .= "define('BASE_URL', '" . $appUrl . "');\n";
                
                // Get the correct path to the main application's includes directory
                $basePath = Utils::getBasePath('');
                Debug::log("Base path: $basePath");
                // Go up 3 levels: php-installer -> jmrashed -> vendor -> flat-management-software
                $mainAppPath = dirname(dirname(dirname($basePath)));
                Debug::log("Main app path: $mainAppPath");
                $configPath = $mainAppPath . '/includes/config.php';
                Debug::log("Writing config to: $configPath");
                
                // Check if the includes directory exists
                $includesDir = dirname($configPath);
                if (!is_dir($includesDir)) {
                    Debug::log("Includes directory doesn't exist, creating: $includesDir");
                    mkdir($includesDir, 0755, true);
                }
                
                if (file_put_contents($configPath, $configContent) === false) {
                    Utils::setAlert('danger', 'Failed to write application config file.');
                    $this->showStep();
                    return;
                }
                Debug::log("Config file written successfully");

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
                Debug::log("Processing finish step");
                $this->installer->createLockFile();
                Debug::log("Lock file created");
                session_destroy(); // Clear all session data
                Debug::log("Session destroyed");
                Debug::log("Redirecting to main application");
                header('Location: /');
                exit;
                break;
            default:
                Utils::redirect(Utils::getBasePath());
                break;
        }

        $nextStep = $this->installer->getCurrentStep();
        Debug::log("Redirecting to: install?step=$nextStep");
        header('Location: install?step=' . $nextStep);
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