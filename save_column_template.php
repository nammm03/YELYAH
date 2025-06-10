<?php
include('dbconnection.php');

$data = json_decode(file_get_contents("php://input"), true);
$account = preg_replace('/[^a-zA-Z0-9_]/', '', $data['account']);
$order = json_encode($data['column_order']); // Save as JSON array

// You can store this in a new table like 'column_templates'
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS column_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account VARCHAR(255) UNIQUE,
    column_order TEXT
)");

$stmt = $conn->prepare("INSERT INTO column_templates (account, column_order)
    VALUES (?, ?) ON DUPLICATE KEY UPDATE column_order = VALUES(column_order)");
$stmt->bind_param("ss", $account, $order);
$stmt->execute();

echo json_encode(['status' => 'success']);
