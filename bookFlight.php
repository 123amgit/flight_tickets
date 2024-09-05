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

    $query = "INSERT INTO bookings (user_id, flight_id, status) VALUES (?, ?, 'Confirmed')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $flight_id);

    if ($stmt->execute()) {
        echo "Lot został pomyślnie zarezerwowany!";
    } else {
        echo "Błąd rezerwacji: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja Lotu - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Rezerwacja Lotu</h1>
        <p>Twoja rezerwacja została pomyślnie zrealizowana.</p>
        <p><a href="index.php">Powrót do strony głównej</a></p>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
