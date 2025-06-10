<?php
include('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account_id'])) {
    $accountId = intval($_POST['account_id']);

    $deleteQuery = "DELETE FROM accounts WHERE id = $accountId";
    if (mysqli_query($conn, $deleteQuery)) {
        // test.php (after successful operation)
        header("Location: accounts_manager.php#accounts");
        exit;
    } else {
        echo "Error deleting account: " . mysqli_error($conn);
    }
}
