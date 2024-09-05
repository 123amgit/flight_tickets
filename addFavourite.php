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

    // Sprawdzenie, czy lot już jest w ulubionych
    $query = "SELECT * FROM favourites WHERE user_id = ? AND flight_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Dodaj lot do ulubionych
        $query = "INSERT INTO favourites (user_id, flight_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $flight_id);
        $stmt->execute();
    }

    // Po dodaniu wróć na stronę wyszukiwania
    header("Location: index.php");
    exit();
}
