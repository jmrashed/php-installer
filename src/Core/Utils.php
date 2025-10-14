<?php

namespace Installer\Core;

class Utils
{
    public static function getBasePath($path = '')
    {
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

    public static function sanitizeInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
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