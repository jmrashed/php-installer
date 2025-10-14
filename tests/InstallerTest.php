<?php

require_once __DIR__ . '/../src/Core/Installer.php';
require_once __DIR__ . '/../src/Core/SystemChecker.php';
require_once __DIR__ . '/../src/Core/DatabaseManager.php';
require_once __DIR__ . '/../src/Core/ConfigWriter.php';
require_once __DIR__ . '/../src/Core/AdminCreator.php';
require_once __DIR__ . '/../src/Core/Utils.php';
require_once __DIR__ . '/../src/Controllers/StepController.php';

use Installer\Core\Installer;
use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\Utils;

class InstallerTest
{
    private $passed = 0;
    private $failed = 0;

    public function runAllTests()
    {
        echo "Running PHP Installer Tests...\n\n";
        
        $this->testInstallerConstruction();
        $this->testStepNavigation();
        $this->testSystemChecker();
        $this->testDatabaseManager();
        $this->testUtils();
        $this->testStepController();
        
        echo "\n=== Test Results ===\n";
        echo "Passed: {$this->passed}\n";
        echo "Failed: {$this->failed}\n";
        echo "Total: " . ($this->passed + $this->failed) . "\n";
        
        return $this->failed === 0;
    }

    private function testInstallerConstruction()
    {
        echo "Testing Installer Construction...\n";
        
        try {
            $installer = new Installer();
            $this->assert($installer->getCurrentStep() === 'welcome', 'Default step should be welcome');
            $this->assert($installer->getTotalSteps() === 8, 'Should have 8 total steps');
            $this->assert(count($installer->getSteps()) === 8, 'Steps array should have 8 items');
            echo "✓ Installer construction tests passed\n\n";
        } catch (Exception $e) {
            $this->fail("Installer construction failed: " . $e->getMessage());
        }
    }

    private function testStepNavigation()
    {
        echo "Testing Step Navigation...\n";
        
        try {
            $installer = new Installer();
            
            // Test step index
            $this->assert($installer->getStepIndex('welcome') === 0, 'Welcome should be step 0');
            $this->assert($installer->getStepIndex('finish') === 7, 'Finish should be step 7');
            
            // Test next step
            $installer->setNextStep();
            $this->assert($installer->getCurrentStep() === 'license', 'Next step should be license');
            
            // Test previous step
            $installer->setPreviousStep();
            $this->assert($installer->getCurrentStep() === 'welcome', 'Previous step should be welcome');
            
            echo "✓ Step navigation tests passed\n\n";
        } catch (Exception $e) {
            $this->fail("Step navigation failed: " . $e->getMessage());
        }
    }

    private function testSystemChecker()
    {
        echo "Testing System Checker...\n";
        
        try {
            if (!defined('INSTALLER_BASE_PATH')) {
                define('INSTALLER_BASE_PATH', __DIR__ . '/..');
            }
            $systemChecker = new SystemChecker();
            $result = $systemChecker->checkSystem();
            $requirements = $systemChecker->getRequirements();
            
            $this->assert(is_bool($result), 'checkSystem should return boolean');
            $this->assert(is_array($requirements), 'getRequirements should return array');
            $this->assert(isset($requirements['php_version']), 'Should check PHP version');
            $this->assert(isset($requirements['extensions']), 'Should check extensions');
            
            echo "✓ System checker tests passed\n\n";
        } catch (Exception $e) {
            $this->fail("System checker failed: " . $e->getMessage());
        }
    }

    private function testDatabaseManager()
    {
        echo "Testing Database Manager...\n";
        
        try {
            $dbManager = new DatabaseManager('localhost', '3306', 'test_db', 'root', '');
            
            // Test connection (may fail if no database, but should not throw exception)
            $connectionResult = $dbManager->testConnection();
            $this->assert(is_bool($connectionResult), 'testConnection should return boolean');
            
            $errors = $dbManager->getErrors();
            $this->assert(is_array($errors), 'getErrors should return array');
            
            echo "✓ Database manager tests passed\n\n";
        } catch (Exception $e) {
            $this->fail("Database manager failed: " . $e->getMessage());
        }
    }

    private function testUtils()
    {
        echo "Testing Utils...\n";
        
        try {
            // Test path generation
            $basePath = Utils::getBasePath('test');
            $this->assert(is_string($basePath), 'getBasePath should return string');
            
            // Test sanitization
            $sanitized = Utils::sanitizeInput('<script>alert("test")</script>');
            $this->assert(!strpos($sanitized, '<script>'), 'Should sanitize HTML');
            
            // Test random string generation
            $randomString = Utils::generateRandomString(16);
            $this->assert(strlen($randomString) === 16, 'Should generate string of correct length');
            
            echo "✓ Utils tests passed\n\n";
        } catch (Exception $e) {
            $this->fail("Utils failed: " . $e->getMessage());
        }
    }

    private function testStepController()
    {
        echo "Testing Step Controller...\n";
        
        try {
            if (!defined('INSTALLER_BASE_PATH')) {
                define('INSTALLER_BASE_PATH', __DIR__ . '/..');
            }
            
            $installer = new Installer();
            $stepController = new \Installer\Controllers\StepController($installer);
            
            $this->assert(is_object($stepController), 'StepController should be instantiated');
            
            echo "✓ Step controller tests passed\n\n";
        } catch (Exception $e) {
            $this->fail("Step controller failed: " . $e->getMessage());
        }
    }

    private function assert($condition, $message)
    {
        if ($condition) {
            $this->passed++;
        } else {
            $this->failed++;
            echo "✗ FAILED: $message\n";
        }
    }

    private function fail($message)
    {
        $this->failed++;
        echo "✗ FAILED: $message\n\n";
    }
}

// Run tests if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $test = new InstallerTest();
    $success = $test->runAllTests();
    exit($success ? 0 : 1);
}