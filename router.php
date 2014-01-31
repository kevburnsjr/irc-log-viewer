<?php

if (preg_match('/\.(css|js|png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    require __DIR__ . '/index.php';
}
