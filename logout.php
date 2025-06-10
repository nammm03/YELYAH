<?php
session_start();
require 'dbconnection.php'; 
if (isset($_SESSION['session_id'])) {
    $session_id = $_SESSION['session_id'];
    $logout_time = date('Y-m-d H:i:s');

    // Update logout_time in the database
    $stmt = $conn->prepare("UPDATE sessions SET logout_time = ? WHERE id = ?");
    $stmt->bind_param("si", $logout_time, $session_id);
    $stmt->execute();
    $stmt->close();
}

// Clear and destroy session
$_SESSION = [];
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: index.php");
exit();
