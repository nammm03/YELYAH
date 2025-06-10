<?php
session_start();
require 'dbconnection.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            $login_time = date('Y-m-d H:i:s');
            $stmt2 = $conn->prepare("INSERT INTO sessions (username, login_time) VALUES (?, ?)");
            $stmt2->bind_param("ss", $username, $login_time);
            $stmt2->execute();
            $_SESSION['session_id'] = $stmt2->insert_id;

            $response = ['success' => true, 'message' => 'Login successful.'];
        } else {
            $response['message'] = 'Incorrect password.';
        }
    } else {
        $response['message'] = 'User not found.';
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
