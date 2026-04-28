<?php
session_start();

// Destroy session
$_SESSION = [];
session_destroy();

// Clear all cookies
if (isset($_COOKIE)) {
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/');
    }
}

// Redirect to login page
header('Location: login.php');
exit;
?>
