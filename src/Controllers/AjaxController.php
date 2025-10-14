<?php

namespace Installer\Controllers;

use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\Utils;

class AjaxController
{
    public function handleRequest()
    {
        header('Content-Type: application/json');

        $action = $_POST['action'] ?? '';
        $response = ['success' => false, 'message' => 'Invalid action.'];

        if (!Utils::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $response['message'] = 'Invalid CSRF token.';
            echo json_encode($response);
            return;
        }

        switch ($action) {
            case 'check_db_connection':
                $response = $this->checkDbConnection();
                break;
            case 'check_system_requirements':
                $response = $this->checkSystemRequirements();
                break;
            default:
                // Handled by initial default response
                break;
        }

        echo json_encode($response);
    }

    private function checkDbConnection()
    {
        $dbHost = Utils::sanitizeInput($_POST['db_host'] ?? '');
        $dbPort = Utils::sanitizeInput($_POST['db_port'] ?? '');
        $dbName = Utils::sanitizeInput($_POST['db_name'] ?? '');
        $dbUsername = Utils::sanitizeInput($_POST['db_username'] ?? '');
        $dbPassword = $_POST['db_password'] ?? ''; // Don't sanitize password

        if (empty($dbHost) || empty($dbPort) || empty($dbUsername)) {
            return ['success' => false, 'message' => 'Host, Port, and Username are required.'];
        }

        $dbManager = new DatabaseManager($dbHost, $dbPort, $dbName, $dbUsername, $dbPassword);
        if ($dbManager->testConnection()) {
            return ['success' => true, 'message' => 'Database connection successful!'];
        } else {
            return ['success' => false, 'message' => implode(', ', $dbManager->getErrors())];
        }
    }

    private function checkSystemRequirements()
    {
        $systemChecker = new SystemChecker();
        $systemChecker->checkSystem();
        $errors = $systemChecker->getErrors();

        if (empty($errors)) {
            return ['success' => true, 'message' => 'All system requirements met!'];
        } else {
            return ['success' => false, 'message' => 'System requirements not met.', 'errors' => $errors];
        }
    }
}