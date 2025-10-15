<?php
require_once __DIR__ . '/../src/autoload.php';

use Installer\Core\Installer;

$installer = new Installer();
$installer->handle();
