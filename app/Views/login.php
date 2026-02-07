<?php
// Forward-compatible top-level login view. Includes the auth/login.php view for content.
// This file exists because some routes expect `app/Views/login.php`.
$include = __DIR__ . '/auth/login.php';
if (file_exists($include)) {
    require $include;
} else {
    echo "Login view not found: " . $include;
}
