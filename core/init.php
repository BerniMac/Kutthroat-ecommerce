<?php
session_start();

// Load config first - contains DB credentials and constants
require_once $_SERVER['DOCUMENT_ROOT'] . '/tutorial/config.php';

// Database connection using constants from config.php
$DBConnect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($DBConnect->connect_errno) {
    die('Database connection failed: ' . $DBConnect->connect_error);
}

// Include helpers
require_once BASEURL . 'helper/helpers.php';

// Retrieve cart cookie if set
$cart_id = '';
if (isset($_COOKIE[CART_COOKIE])) {
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}
