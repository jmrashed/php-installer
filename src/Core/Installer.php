<?php

namespace Installer\Core;

use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\ConfigWriter;
use Installer\Core\AdminCreator;
use Installer\Core\Utils;

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
        // In a real application, this would read from a session or a temporary file
        // For now, we'll default to the first step
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
}