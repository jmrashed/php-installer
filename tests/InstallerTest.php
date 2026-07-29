<?php

namespace Installer\Tests;

use Installer\Core\Installer;
use Installer\Core\SystemChecker;
use Installer\Core\DatabaseManager;
use Installer\Core\Utils;
use Installer\Controllers\StepController;
use PHPUnit\Framework\TestCase;

class InstallerTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('INSTALLER_BASE_PATH')) {
            define('INSTALLER_BASE_PATH', dirname(__DIR__));
        }
        $_SESSION = [];
        $_GET = [];
    }

    public function testInstallerConstruction(): void
    {
        $installer = new Installer();

        $this->assertSame('welcome', $installer->getCurrentStep(), 'Default step should be welcome');
        $this->assertSame(8, $installer->getTotalSteps(), 'Should have 8 total steps');
        $this->assertCount(8, $installer->getSteps(), 'Steps array should have 8 items');
    }

    public function testStepNavigation(): void
    {
        $installer = new Installer();

        $this->assertSame(0, $installer->getStepIndex('welcome'), 'Welcome should be step 0');
        $this->assertSame(7, $installer->getStepIndex('finish'), 'Finish should be step 7');

        $installer->setNextStep();
        $this->assertSame('license', $installer->getCurrentStep(), 'Next step should be license');

        $installer->setPreviousStep();
        $this->assertSame('welcome', $installer->getCurrentStep(), 'Previous step should be welcome');
    }

    public function testSystemChecker(): void
    {
        $systemChecker = new SystemChecker();
        $result = $systemChecker->checkSystem();
        $requirements = $systemChecker->getRequirements();

        $this->assertIsBool($result, 'checkSystem should return boolean');
        $this->assertIsArray($requirements, 'getRequirements should return array');
        $this->assertArrayHasKey('php_version', $requirements, 'Should check PHP version');
        $this->assertArrayHasKey('extensions', $requirements, 'Should check extensions');
    }

    public function testDatabaseManagerReturnsBooleanWithoutThrowing(): void
    {
        // No real database is guaranteed to be reachable here; testConnection()
        // must degrade to a caught, reported failure rather than throwing.
        $dbManager = new DatabaseManager('127.0.0.1', '3306', 'nonexistent_test_db', 'root', 'wrong-password');

        $connectionResult = $dbManager->testConnection();
        $this->assertIsBool($connectionResult, 'testConnection should return boolean');

        $errors = $dbManager->getErrors();
        $this->assertIsArray($errors, 'getErrors should return array');
    }

    public function testUtilsGetBasePath(): void
    {
        $basePath = Utils::getBasePath('test');
        $this->assertIsString($basePath);
    }

    public function testUtilsSanitizeInputNormalizesWithoutEscaping(): void
    {
        // sanitizeInput() only trims/normalizes; it must NOT HTML-escape. HTML
        // escaping happens once, at output, via Utils::e() (see CHANGELOG 2.1.0 -
        // escaping here as well would double-encode redisplayed/persisted values).
        $sanitized = Utils::sanitizeInput('  <script>alert("test")</script>  ');
        $this->assertSame('<script>alert("test")</script>', $sanitized);
    }

    public function testUtilsGenerateRandomString(): void
    {
        $randomString = Utils::generateRandomString(16);
        $this->assertSame(16, strlen($randomString));
    }

    public function testStepControllerCanBeInstantiated(): void
    {
        $installer = new Installer();
        $stepController = new StepController($installer);

        $this->assertInstanceOf(StepController::class, $stepController);
    }

    /**
     * Regression test for security-audit.md C2 (XSS via unescaped view output).
     */
    public function testUtilsEscapesQuotesAndHtmlTags(): void
    {
        $escaped = Utils::e('X\'); <script>alert(1)</script>');

        $this->assertStringNotContainsString('<script>', $escaped);
        $this->assertStringContainsString('&#039;', $escaped);
        $this->assertStringContainsString('&lt;script&gt;', $escaped);
    }

    /**
     * Regression test for feature-gap-analysis.md's re-install gap: the lock
     * file is written but was never checked by Installer::handle(), so the
     * installer could be re-run indefinitely against a live database.
     */
    public function testIsInstalledReflectsLockFile(): void
    {
        $installer = new Installer();
        $lockFile = Utils::getLockFile();

        if (file_exists($lockFile)) {
            unlink($lockFile);
        }

        $this->assertFalse($installer->isInstalled(), 'Should not be installed before the lock file exists');

        $installer->createLockFile();
        $this->assertTrue($installer->isInstalled(), 'Should be installed once the lock file is written');

        $installer->deleteLockFile();
        $this->assertFalse($installer->isInstalled(), 'Should not be installed after the lock file is removed');
    }
}
