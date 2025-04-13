<?php

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load the .env file
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// Optional: You can check if variables are loaded correctly
if (!getenv('DB_HOST') || !getenv('DB_NAME')) {
die('Environment variables are not loaded properly.');
}
