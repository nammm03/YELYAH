<?php
require 'dbconnection.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($username && $password && $role) {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already taken.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $message = "Registration failed.";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $message = "All fields are required.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
        <?php if ($message): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php" class="space-y-4">
            <div>
                <label class="block mb-1">Username</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">Register</button>
        </form>
        <p class="mt-4 text-center text-sm">
            Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </div>
</body>
</html>
