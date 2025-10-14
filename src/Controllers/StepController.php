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
            case 'license':
                // No specific POST data to process, just move to next step
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
                $dbHost = Utils::sanitizeInput($_POST['db_host'] ?? '');
                $dbPort = Utils::sanitizeInput($_POST['db_port'] ?? '');
                $dbName = Utils::sanitizeInput($_POST['db_name'] ?? '');
                $dbUsername = Utils::sanitizeInput($_POST['db_username'] ?? '');
                $dbPassword = $_POST['db_password'] ?? ''; // Don't sanitize password

                if (empty($dbHost) || empty($dbPort) || empty($dbName) || empty($dbUsername)) {
                    Utils::setAlert('danger', 'All database fields are required.');
                    $this->showStep();
                    return;
                }

                $_SESSION['db_host'] = $dbHost;
                $_SESSION['db_port'] = $dbPort;
                $_SESSION['db_name'] = $dbName;
                $_SESSION['db_username'] = $dbUsername;
                $_SESSION['db_password'] = $dbPassword;

                $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword);
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

                $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword);
                $sqlFilePath = Utils::getBasePath('database/db.sql');

                if (!$dbManager->importSqlFile($sqlFilePath)) {
                    foreach ($dbManager->getErrors() as $error) {
                        Utils::setAlert('danger', $error);
                    }
                    $this->showStep();
                    return;
                }
                Utils::setAlert('success', 'Database imported successfully!');
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
                    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName}", $dbUsername, $dbPassword);
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
                } catch (PDOException $e) {
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

        Utils::redirect(Utils::getBasePath('index.php?step=' . $this->installer->getCurrentStep()));
    }

    private function renderView($step, $data = [])
    {
        extract($data); // Extract data to make it available in the view
        $installer = $this->installer; // Make installer object available in views
        $alerts = Utils::getAlerts(); // Get and clear alerts

        include Utils::getBasePath('src/Views/layouts/header.php');
        include Utils::getBasePath("src/Views/steps/{$step}.php");
        include Utils::getBasePath('src/Views/layouts/footer.php');
    }
}