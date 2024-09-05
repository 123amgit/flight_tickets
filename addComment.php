<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = $_POST['comment'];
    $flight_id = $_POST['flight_id'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO comments (user_id, flight_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $flight_id, $comment);

    if ($stmt->execute()) {
        echo "Komentarz został dodany.";
    } else {
        echo "Błąd dodawania komentarza: " . $conn->error;
    }

    header("Location: flightDetails.php?id=" . $flight_id);
    exit();
}
