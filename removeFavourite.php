<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['flight_id'])) {
    $flight_id = $_GET['flight_id'];
    $user_id = $_SESSION['user_id'];

    // Usunięcie lotu z ulubionych
    $query = "DELETE FROM favourites WHERE user_id = ? AND flight_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $flight_id);
    $stmt->execute();
}

header('Location: favourites.php'); // Powrót do zakładki ulubione
exit();
?>
