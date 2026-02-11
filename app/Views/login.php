<?php
$include = __DIR__ . '/auth/login.php';
if (file_exists($include)) {
    require $include;
} else {
    echo "Login view not found: " . $include;
}
