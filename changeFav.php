<?php
include('db.php');
session_start();

if (!isset($_SESSION['username'])) {
    echo "unauthorized";
    exit();
}

$flight_id = $_POST['flightId'];
$user_id = $_SESSION['user_id'];

// Check if the flight is already a favorite
$query = "SELECT * FROM favorites WHERE user_id='$user_id' AND flight_id='$flight_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Remove from favorites
    $query = "DELETE FROM favorites WHERE user_id='$user_id' AND flight_id='$flight_id'";
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    // Add to favorites
    $query = "INSERT INTO favorites (user_id, flight_id) VALUES ('$user_id', '$flight_id')";
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
