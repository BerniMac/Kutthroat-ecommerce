<?php
if (!defined('BASEURL')) {
    define('BASEURL', $_SERVER['DOCUMENT_ROOT'] . '/tutorial/');
}

if (!defined('CART_COOKIE')) {
    // Generate a random string at https://randomkeygen.com
    define('CART_COOKIE', 'YOUR_RANDOM_STRING_HERE');
}

if (!defined('CART_COOKIE_EXPIRE')) {
    define('CART_COOKIE_EXPIRE', time() + (86400 * 30));
}

if (!defined('TAXRATE')) {
    define('TAXRATE', 0.15);
}

if (!defined('CURRENCY')) {
    define('CURRENCY', 'zar');
}

if (!defined('CHECKOUTMODE')) {
    define('CHECKOUTMODE', 'TEST');
}

if (CHECKOUTMODE === 'TEST') {
    if (!defined('STRIPE_PRIVATE')) {
        define('STRIPE_PRIVATE', 'sk_test_YOUR_KEY_HERE');
    }
    if (!defined('STRIPE_PUBLIC')) {
        define('STRIPE_PUBLIC', 'pk_test_YOUR_KEY_HERE');
    }
} elseif (CHECKOUTMODE === 'LIVE') {
    if (!defined('STRIPE_PRIVATE')) {
        define('STRIPE_PRIVATE', 'sk_live_YOUR_KEY_HERE');
    }
    if (!defined('STRIPE_PUBLIC')) {
        define('STRIPE_PUBLIC', 'pk_live_YOUR_KEY_HERE');
    }
}

// Database credentials
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'YOUR_DB_USERNAME');
if (!defined('DB_PASS')) define('DB_PASS', 'YOUR_DB_PASSWORD');
if (!defined('DB_NAME')) define('DB_NAME', 'YOUR_DB_NAME');