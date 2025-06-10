<?php
include('dbconnection.php');

$name = $_POST['name'];
$parent_id = !empty($_POST['hasSub']) ? $_POST['parent_id'] : NULL;

$query = "INSERT INTO accounts (name, parent_id) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $name, $parent_id);
mysqli_stmt_execute($stmt);

// test.php (after successful operation)
header("Location: accounts_manager.php#accounts");
exit;
