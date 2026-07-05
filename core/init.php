<?php
session_start();

require_once dirname(__DIR__) . '/config.php';

// Database connection using constants from config.php
$DBConnect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($DBConnect->connect_errno) {
    die('Database connection failed: ' . $DBConnect->connect_error);
}


 require_once dirname(__DIR__) . '/helper/helpers.php'; 

if (!defined('BASEURL')) {
    define('BASEURL', dirname(__DIR__) . '/');
}

// Retrieve cart cookie if set
$cart_id = '';
if (isset($_COOKIE[CART_COOKIE])) {
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}
