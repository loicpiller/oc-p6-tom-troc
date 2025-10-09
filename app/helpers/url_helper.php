<?php

use MVC\Core\Config;

$config = Config::getInstance();
$baseUrl = $config->get('BASE_URL');

if (!function_exists('action_url')) {
    function action_url(string $action, array $params = []): string
    {
        global $baseUrl;
        $url = $baseUrl . '/' . ltrim($action, '/');
        if (!empty($params)) {
            $queryString = http_build_query($params);
            $url .= '?' . $queryString;
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
