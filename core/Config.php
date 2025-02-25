<?php

namespace MVC\Core;

use Exception;

class Config extends Singleton
{
    private array $settings = [];

    /**
     * Loads a configuration file from a given file path.
     *
     * The configuration file must be a PHP file returning an associative array.
     * The array will be stored in the settings property.
     *
     * @param string $filePath The path to the configuration file
     * @return void
     * @throws Exception  If the file is not found or has an invalid format
     */
    public function loadFromFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("Configuration file not found: $filePath");
        }
        $config = require $filePath;
        if (!is_array($config)) {
            throw new Exception("Invalid configuration file format: $filePath. Expected an associative array.");
        }
        $this->settings = $config;
    }

    /**
     * Gets a configuration value from the settings array.
     *
     * If the key does not exist, returns null.
     *
     * @param string $key The key of the configuration value to get
     * @return mixed The value of the configuration, or null if not found
     */
    public function get(string $key): mixed
    {
        return $this->settings[$key] ?? null;
    }
}
