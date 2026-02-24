<?php
// Start session
session_start();

// Database connection
$location = 'localhost';
$user = 'root';
$password = 'root';
$database = 'Kutthroat';

$DBConnect = new mysqli($location, $user, $password, $database);

// Check connection
if ($DBConnect->connect_errno) {
    die('Database connection failed: ' . $DBConnect->connect_error);
}

// Base directory
define('BASEURL', __DIR__ . '/../');

// Include config and helper files
require_once BASEURL . 'config.php';
require_once BASEURL . 'helper/helpers.php';

// Retrieve cart cookie if set
$cart_id = '';
if (isset($_COOKIE['CART_COOKIE'])) {
    $cart_id = sanitize($_COOKIE['CART_COOKIE']);
}
?>
