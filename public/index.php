<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MVC\Core\Config;
use MVC\Core\ErrorHandler;
use MVC\Core\Router;

// Set the error handler
set_exception_handler([ErrorHandler::class, 'handle']);

// Load configuration settings
Config::getInstance()->loadFromFile(__DIR__ . '/../config/config.php');

$router = Router::getInstance();
$router->loadRoutesFromFile(__DIR__ . '/../config/routes.php');

$router->dispatch();