<?php
include 'dbconnection.php';

if (!isset($_POST['id'], $_POST['column'], $_POST['value'])) {
    http_response_code(400);
    echo "Missing parameters";
    exit;
}

$id = intval($_POST['id']);
$field = $_POST['column'];
$value = $_POST['value'];

$allowed = ['account', 'origin', 'destination', 'truck_size', 'rate', 'driver', 'helper1', 'helper2'];
if (!in_array($field, $allowed)) {
    http_response_code(400);
    echo "Invalid field";
    exit;
}

$stmt = $conn->prepare("UPDATE otrp SET `$field` = ? WHERE id = ?");
$stmt->bind_param("si", $value, $id);

if ($stmt->execute()) {
    http_response_code(200); // Explicitly set 200
    echo "Updated successfully";
} else {
    http_response_code(500);
    echo "Error: " . $stmt->error;
}
