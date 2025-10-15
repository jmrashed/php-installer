<?php

namespace Installer\Core;

use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\ConfigWriter;
use Installer\Core\AdminCreator;
use Installer\Core\Utils;
use Installer\Core\Debug;

class Installer
{
    private $currentStep;
    private $totalSteps;
    private $steps = [
        'welcome',
        'license',
        'system_check',
        'db_config',
        'db_import',
        'app_config',
        'admin_account',
        'finish'
    ];

    public function __construct()
    {
        $this->totalSteps = count($this->steps);
        $this->currentStep = $this->getCurrentStepFromSession();
    }

    private function getCurrentStepFromSession()
    {
        // Check URL parameter first, then session, then default
        if (isset($_GET['step']) && in_array($_GET['step'], $this->steps)) {
            $this->currentStep = $_GET['step'];
            $_SESSION['installer_step'] = $this->currentStep;
            return $this->currentStep;
        }
        return isset($_SESSION['installer_step']) ? $_SESSION['installer_step'] : 'welcome';
    }

    public function getStepIndex($stepName)
    {
        return array_search($stepName, $this->steps);
    }

    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    public function getTotalSteps()
    {
        return $this->totalSteps;
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function setNextStep()
    {
        $currentIndex = $this->getStepIndex($this->currentStep);
        if ($currentIndex !== false && $currentIndex < $this->totalSteps - 1) {
            $this->currentStep = $this->steps[$currentIndex + 1];
            $_SESSION['installer_step'] = $this->currentStep;
        }
    }

    public function setPreviousStep()
    {
        $currentIndex = $this->getStepIndex($this->currentStep);
        if ($currentIndex !== false && $currentIndex > 0) {
            $this->currentStep = $this->steps[$currentIndex - 1];
            $_SESSION['installer_step'] = $this->currentStep;
        }
    }

    public function runStep($step)
    {
        // This method would handle the logic for each step
        // For now, it just returns true
        return true;
    }

    public function isInstalled()
    {
        // Check for an installation lock file
        return file_exists(Utils::getLockFile());
    }

    public function createLockFile()
    {
        file_put_contents(Utils::getLockFile(), time());
    }

    public function deleteLockFile()
    {
        if (file_exists(Utils::getLockFile())) {
            unlink(Utils::getLockFile());
        }
    }

    public function handle()
    {
        session_start();
        
        if (!defined('INSTALLER_BASE_PATH')) {
            define('INSTALLER_BASE_PATH', dirname(__DIR__, 2));
        }

        Debug::log("Starting installer handle()");
        Debug::log("Current step: " . $this->getCurrentStep());
        Debug::log("Request method: " . $_SERVER['REQUEST_METHOD']);
        Debug::log("Base path: " . INSTALLER_BASE_PATH);
        Debug::log("GET params: " . print_r($_GET, true));
        Debug::log("Session data: " . print_r($_SESSION, true));

        try {
            Debug::log("Creating StepController");
            $stepController = new \Installer\Controllers\StepController($this);
            Debug::log("StepController created");
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Debug::log("Processing POST request");
                $stepController->postStep();
            } else {
                Debug::log("Processing GET request");
                $stepController->showStep();
            }
            Debug::log("Step processing completed");
        } catch (Exception $e) {
            Debug::log("Exception in handle(): " . $e->getMessage());
            Debug::log("File: " . $e->getFile());
            Debug::log("Line: " . $e->getLine());
        } catch (Error $e) {
            Debug::log("Fatal error in handle(): " . $e->getMessage());
            Debug::log("File: " . $e->getFile());
            Debug::log("Line: " . $e->getLine());
        }
    }

    private function debug($message)
    {
        if (isset($_GET['debug']) || defined('INSTALLER_DEBUG')) {
            echo "<div style='background:#f0f0f0;padding:5px;margin:2px;border-left:3px solid #007cba;font-family:monospace;font-size:12px;'>DEBUG: {$message}</div>";
        }
    }
}