<?php

namespace Installer\Core;

class ConfigWriter
{
    private $errors = [];

    public function writeEnvFile($envData)
    {
        $envTemplatePath = Utils::getTemplatePath('env_template.php');
        if (!file_exists($envTemplatePath)) {
            $this->errors[] = "Environment template file not found: {$envTemplatePath}";
            return false;
        }

        $templateContent = file_get_contents($envTemplatePath);
        if ($templateContent === false) {
            $this->errors[] = "Failed to read environment template file: {$envTemplatePath}";
            return false;
        }

        $envContent = $templateContent;
        foreach ($envData as $key => $value) {
            $envContent = str_replace("{{{$key}}}", $value, $envContent);
        }

        $envFilePath = Utils::getBasePath('.env');
        if (file_put_contents($envFilePath, $envContent) === false) {
            $this->errors[] = "Failed to write .env file: {$envFilePath}";
            return false;
        }

        return true;
    }

    public function writeConfigFile($configData)
    {
        $configTemplatePath = Utils::getTemplatePath('config_template.php');
        if (!file_exists($configTemplatePath)) {
            $this->errors[] = "Config template file not found: {$configTemplatePath}";
            return false;
        }

        $templateContent = file_get_contents($configTemplatePath);
        if ($templateContent === false) {
            $this->errors[] = "Failed to read config template file: {$configTemplatePath}";
            return false;
        }

        $configContent = $templateContent;
        foreach ($configData as $key => $value) {
            $configContent = str_replace("{{{$key}}}", $value, $configContent);
        }

        $configFilePath = Utils::getConfigPath('installer.php');
        if (file_put_contents($configFilePath, $configContent) === false) {
            $this->errors[] = "Failed to write config file: {$configFilePath}";
            return false;
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}