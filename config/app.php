<?php
ob_start();
session_start();

// Todo: refactor env variables
$sep = '/';
$base_url = dirname(__DIR__) . $sep;

if(!defined('DEFAULT_ADMIN_ID')) define('DEFAULT_ADMIN_ID', 1);

require $base_url . 'vendor/autoload.php';

require $base_url . 'app/includes/functions.php';

require $base_url . '/config/database.php';

