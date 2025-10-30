<?php
// Destroy session and redirect to Landing
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Unset all session variables
$_SESSION = [];

// Destroy the session cookie if it exists
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

// Finally destroy the session
session_destroy();

header('Location: Landing.php');
exit;
?>


