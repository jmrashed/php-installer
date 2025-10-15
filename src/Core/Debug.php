<?php

namespace Installer\Core;

class Debug
{
    private static $debugEnabled = null;
    
    public static function isEnabled()
    {
        if (self::$debugEnabled === null) {
            // Check .env file for APP_DEBUG
            $envPath = dirname(dirname(dirname(dirname(__DIR__)))) . '/.env';
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                if (preg_match('/APP_DEBUG\s*=\s*(true|1|yes|on)/i', $envContent)) {
                    self::$debugEnabled = true;
                } else {
                    self::$debugEnabled = false;
                }
            } else {
                // Fallback: check for debug parameter or constant
                self::$debugEnabled = isset($_GET['debug']) || defined('INSTALLER_DEBUG');
            }
        }
        
        return self::$debugEnabled;
    }
    
    public static function log($message)
    {
        if (self::isEnabled()) {
            echo "<div style='background:#f0f0f0;padding:5px;margin:2px;border-left:3px solid #007cba;font-family:monospace;font-size:12px;'>[DEBUG] {$message}</div>";
        }
    }
}