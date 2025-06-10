<?php
// get_account.php
include 'dbconnection.php';

$sql = "SELECT id, name FROM accounts ORDER BY name ASC";
$result = mysqli_query($conn, $sql);

$options = '';
while ($row = mysqli_fetch_assoc($result)) {
    $options .= "<option value='{$row['id']}'>{$row['name']}</option>";
}

echo $options;
