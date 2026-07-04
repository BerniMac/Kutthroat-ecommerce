<?php
function display_error($errors) {
    $display = '<ul class="big danger">';
    foreach ($errors as $error) {
        $display .= '<li class="text danger">' . $error . '</li>';
    }
    $display .= '</ul>';
    return $display;
}

/* function sanitize($dirty) {
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

function money($number) {
    return 'R' . number_format($number, 2); // Updated to South African Rand symbol
} */

function sanitize($dirty) {
    return htmlentities(trim($dirty), ENT_QUOTES, 'UTF-8');
}

function money($number) {
    return 'R' . number_format($number, 2);
}

function display_errors($errors) {
    $display = '<ul class="list-unstyled">';
    foreach ($errors as $error) {
        $display .= '<li class="text-danger">' . $error . '</li>';
    }
    $display .= '</ul>';
    return $display;
}

// Converts "S:5,M:10,L:3" → [['size'=>'S','quantity'=>5], ...]
function sizesToArray($sizeString) {
    $result = [];
    $sizeString = rtrim($sizeString, ',');
    foreach (explode(',', $sizeString) as $pair) {
        $parts = explode(':', $pair);
        if (count($parts) === 2) {
            $result[] = [
                'size'     => trim($parts[0]),
                'quantity' => (int)trim($parts[1]),
            ];
        }
    }
    return $result;
}

// Converts [['size'=>'S','quantity'=>5], ...] → "S:5,M:10,L:3"
function sizesToString($sizesArray) {
    $parts = [];
    foreach ($sizesArray as $s) {
        $parts[] = $s['size'] . ':' . $s['quantity'];
    }
    return implode(',', $parts);
}
?>
