<?php
include('db.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Administrator') {
    header('Location: login.php');
    exit();
}

$flight_id = $_GET['id'];
$query = "DELETE FROM flights WHERE id='$flight_id'";

if (mysqli_query($conn, $query)) {
    header('Location: admin.php');
} else {
    echo "Błąd usuwania lotu: " . mysqli_error($conn);
}
?>
