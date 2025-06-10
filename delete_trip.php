<?php
// delete_trip.php
include 'dbconnection.php'; 

// Decode raw JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check for the ID
if (isset($data['id'])) {
    $id = intval($data['id']);

    // Run the DELETE query
    $stmt = $conn->prepare("DELETE FROM otrp WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing ID']);
}
?>