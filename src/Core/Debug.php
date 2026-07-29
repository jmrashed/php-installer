<?php

namespace Installer\Core;

class Debug
{
    private static $debugEnabled = null;
    
    public static function isEnabled()
    {
        if (self::$debugEnabled === null) {
            $envPath = self::findEnvFile();
            if ($envPath !== null) {
                $envContent = file_get_contents($envPath);
                self::$debugEnabled = (bool) preg_match('/APP_DEBUG\s*=\s*(true|1|yes|on)/i', $envContent);
            } else {
                // No .env file found: default closed. Debug output must never be
                // controllable via an unauthenticated request parameter.
                self::$debugEnabled = false;
            }
        }

        return self::$debugEnabled;
    }

    /**
     * Locate the consuming application's .env file. This package can be
     * installed two ways (per the README): via Composer, landing at
     * vendor/<vendor>/php-installer, or copied directly into the project
     * root — a fixed directory-depth guess only works for the first case.
     * INSTALLER_ENV_PATH lets a consumer override this entirely.
     */
    private static function findEnvFile()
    {
        if (defined('INSTALLER_ENV_PATH')) {
            return file_exists(INSTALLER_ENV_PATH) ? INSTALLER_ENV_PATH : null;
        }

        $candidates = [];
        if (defined('INSTALLER_BASE_PATH')) {
            // Copied directly into the project root: .env sits next to it.
            $candidates[] = rtrim(INSTALLER_BASE_PATH, '/') . '/.env';
            // Installed via Composer at vendor/<vendor>/php-installer: project
            // root is three levels up from the package root.
            $candidates[] = dirname(INSTALLER_BASE_PATH, 3) . '/.env';
        }
        // Last-resort fallback matching this file's own fixed location.
        $candidates[] = dirname(__DIR__, 4) . '/.env';

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
    
    public static function log($message)
    {
        if (self::isEnabled()) {
            echo "<div style='background:#f0f0f0;padding:5px;margin:2px;border-left:3px solid #007cba;font-family:monospace;font-size:12px;'>[DEBUG] {$message}</div>";
        }
    }
}