<?php

namespace Installer\Core;

class Utils
{
    public static function getBasePath($path = '')
    {
        if (!defined('INSTALLER_BASE_PATH')) {
            define('INSTALLER_BASE_PATH', dirname(__DIR__, 2));
        }
        return rtrim(INSTALLER_BASE_PATH, '/') . '/' . ltrim($path, '/');
    }

    public static function getTemplatePath($path = '')
    {
        return self::getBasePath('src/Templates/' . $path);
    }

    public static function getConfigPath($path = '')
    {
        return self::getBasePath('config/' . $path);
    }

    public static function getLockFile()
    {
        return self::getBasePath('storage/installer.lock');
    }

    public static function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Normalize raw input (trim whitespace, undo magic-quotes-style escaping).
     * This does NOT HTML-escape — escaping happens once, at output time, via
     * self::e(). Escaping here as well would double-encode values that get
     * redisplayed or written verbatim into generated files (e.g. app_config).
     */
    public static function sanitizeInput($data)
    {
        return trim(stripslashes($data));
    }

    /**
     * Escape a value for safe HTML output. Use at every point a value is
     * echoed into a view — the single point where HTML escaping should happen.
     */
    public static function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function generateRandomString($length = 16)
    {
        return bin2hex(random_bytes($length / 2));
    }

    public static function getCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateRandomString(32);
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }

    public static function setAlert($type, $message)
    {
        if (!isset($_SESSION['alerts'])) {
            $_SESSION['alerts'] = [];
        }
        $_SESSION['alerts'][] = ['type' => $type, 'message' => $message];
    }

    public static function getAlerts()
    {
        $alerts = isset($_SESSION['alerts']) ? $_SESSION['alerts'] : [];
        unset($_SESSION['alerts']);
        return $alerts;
    }
}
