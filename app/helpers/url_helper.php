<?php

use MVC\Core\Config;

$config = Config::getInstance();
$baseUrl = $config->get('BASE_URL');

if (!function_exists('action_url')) {
    /** @param array<string, mixed> $params */
    function action_url(string $action, array $params = []): string
    {
        global $baseUrl;
        $url = $baseUrl . '/' . ltrim($action, '/');
        foreach ($params as $param => $value) {
            $url = str_replace('{'.$param.'}', $value, $url);
        }
        return $url;
    }
}

if (!function_exists('img_url')) {
    function img_url(string $imagePath): string
    {
        global $baseUrl;
        return $baseUrl . '/public/img/' . ltrim($imagePath, '/');
    }
}
