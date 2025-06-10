<?php
include 'dbconnection.php';

$ids = $_POST['id'] ?? [];
$accounts = $_POST['account'] ?? [];
$origins = $_POST['origin'] ?? [];
$destinations = $_POST['destination'] ?? [];
$truck_sizes = $_POST['truck_size'] ?? [];
$rates = $_POST['rate'] ?? [];
$drivers = $_POST['driver'] ?? [];
$helpers1 = $_POST['helper1'] ?? [];
$helpers2 = $_POST['helper2'] ?? [];

for ($i = 0; $i < count($accounts); $i++) {
    $id = $ids[$i] ?? null;
    $account = mysqli_real_escape_string($conn, trim($accounts[$i]));
    $origin = mysqli_real_escape_string($conn, trim($origins[$i]));
    $destination = mysqli_real_escape_string($conn, trim($destinations[$i]));
    $truck_size = mysqli_real_escape_string($conn, trim($truck_sizes[$i]));

    // Cast numeric fields safely
    $rate = floatval($rates[$i]);
    $driver = floatval($drivers[$i]);
    $helper1 = floatval($helpers1[$i]);
    $helper2 = floatval($helpers2[$i]);

    if ($id && $id !== "") {
        // Update existing record
            $sql = "UPDATE otrp SET 
                account='$account',
                origin='$origin',
                destination='$destination',
                truck_size='$truck_size',
                rate=$rate,
                driver=$driver,
                helper1=$helper1,
                helper2=$helper2
            WHERE id=$id";
} else {
    // Insert new record
    $sql = "INSERT INTO otrp 
                (account, origin, destination, truck_size, rate, driver, helper1, helper2)
            VALUES 
                ('$account', '$origin', '$destination', '$truck_size', $rate, $driver, $helper1, $helper2)";
}


    mysqli_query($conn, $sql);
}

header("Location: trip_manager.php");
exit;