<?php
require_once('./core/init.php');

// Check if required POST variables are set
if (!isset($_POST['product_id'], $_POST['size'], $_POST['available'], $_POST['quantity'])) {
    die("Required fields missing.");
}

// Sanitize inputs
$product_id = sanitize($_POST['product_id']);
$size = sanitize($_POST['size']);
$available = (int)sanitize($_POST['available']);
$quantity = (int)sanitize($_POST['quantity']);

if ($quantity < 1) {
    die("Invalid quantity.");
}

$item = array(array(
    'id'        => $product_id,
    'size'      => $size,
    'quantity'  => $quantity,
));

$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? '.' . $_SERVER['HTTP_HOST'] : false;

// Fetch product info
$productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
if (!$productQ || $productQ->num_rows === 0) {
    die("Product not found.");
}
$product = mysqli_fetch_assoc($productQ);
$_SESSION['success_flash'] = $product['title'] . ' was added to your cart.';

// Check for existing cart
$cart_id = (isset($_COOKIE[CART_COOKIE]) ? sanitize($_COOKIE[CART_COOKIE]) : '');

if ($cart_id !== '') {
    // Existing cart: update items
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}' LIMIT 1");
    if ($cartQ && $cartQ->num_rows > 0) {
        $cart = mysqli_fetch_assoc($cartQ);
        $previous_items = json_decode($cart['items'], true);

        $item_match = false;
        $new_items = [];

        foreach ($previous_items as $pitem) {
            if ($pitem['id'] === $item[0]['id'] && $pitem['size'] === $item[0]['size']) {
                $pitem['quantity'] += $item[0]['quantity'];
                if ($pitem['quantity'] > $available) {
                    $pitem['quantity'] = $available;
                }
                $item_match = true;
            }
            $new_items[] = $pitem;
        }

        if (!$item_match) {
            $new_items = array_merge($item, $previous_items);
        }

        $items_json = json_encode($new_items);
        $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));

        $db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");

        // Reset cookie
        setcookie(CART_COOKIE, '', 1, "/", $domain, false);
        setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
    } else {
        // Cart ID exists but not found in DB
        $cart_id = '';
    }
}

if ($cart_id === '') {
    // New cart
    $items_json = json_encode($item);
    $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));

    $db->query("INSERT INTO cart (items, expire_date) VALUES ('{$items_json}', '{$cart_expire}')");
    $cart_id = $db->insert_id;

    setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
}
?>
