<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header('Location: myBookings.php');
    exit();
}

$booking_id = $_GET['booking_id'];

// Usunięcie rezerwacji
$query = "DELETE FROM bookings WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Rezerwacja została anulowana.";
} else {
    $_SESSION['message'] = "Błąd podczas anulowania rezerwacji.";
}

header('Location: myBookings.php');
exit();
