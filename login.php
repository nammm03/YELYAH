<?php
session_start();
require 'dbconnection.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Get id, password, and role
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // ✅ Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            $login_time = date('Y-m-d H:i:s');
            $stmt2 = $conn->prepare("INSERT INTO sessions (username, login_time) VALUES (?, ?)");
            $stmt2->bind_param("ss", $username, $login_time);
            $stmt2->execute();
            $_SESSION['session_id'] = $stmt2->insert_id;

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
        <?php if ($message): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($message) ?></p>
        <?php elseif (isset($_GET['registered'])): ?>
            <p class="text-green-500 mb-4">Registration successful! Please log in.</p>
        <?php endif; ?>
        <form method="POST" action="login.php" class="space-y-4">
            <div>
                <label class="block mb-1">Username</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Login</button>
        </form>
        <!-- Wala na munang registration -->
        <!-- <p class="mt-4 text-center text-sm">
            Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a>
        </p> -->
    </div>
</body>
</html>
