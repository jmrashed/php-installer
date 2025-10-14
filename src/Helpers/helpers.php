<?php

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return Installer\Core\Utils::getBasePath($path);
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        $config = include Installer\Core\Utils::getConfigPath('installer.php');
        return $config[$key] ?? $default;
    }
}
