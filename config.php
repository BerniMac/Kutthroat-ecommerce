<?php
if (!defined('BASEURL')) {
    define('BASEURL', $_SERVER['DOCUMENT_ROOT'] . '/tutorial/');
}

if (!defined('CART_COOKIE')) {
    define('CART_COOKIE', 'SBwi72UCKlwiqzz2');
}

if (!defined('CART_COOKIE_EXPIRE')) {
    define('CART_COOKIE_EXPIRE', time() + (86400 * 30)); // fixed 86500 to 86400 (seconds in a day)
}

if (!defined('TAXERATE')) {
    define('TAXERATE', 0.15);
}

if (!defined('CURRENCY')) {
    define('CURRENCY', 'RAND');
}

if (!defined('CHECKOUTMODE')) {
    define('CHECKOUTMODE', 'TEST'); // CHANGE TO 'LIVE' WHEN SELLING
}

// Stripe keys based on checkout mode
if (CHECKOUTMODE === 'TEST') {
    if (!defined('STRIPE_PRIVATE')) {
        define('STRIPE_PRIVATE', 'USEYOUROWNKEYS');
    }
    if (!defined('STRIPE_PUBLIC')) {
        define('STRIPE_PUBLIC', 'USEYOUROWNKEYS');
    }
} elseif (CHECKOUTMODE === 'LIVE') {
    if (!defined('STRIPE_PRIVATE')) {
        define('STRIPE_PRIVATE', 'USEYOUROWNKEYS');
    }
    if (!defined('STRIPE_PUBLIC')) {
        define('STRIPE_PUBLIC', 'USEYOUROWNKEYS');
    }
}
?>
