<?php
require_once __DIR__ . '/../src/Core/Installer.php';

use Installer\Core\Installer;

$installer = new Installer();
$installer->handle();
