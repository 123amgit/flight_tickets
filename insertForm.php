<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Administrator') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj nowy lot - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <main>
        <section>
            <h1>Dodaj nowy lot</h1>
            <form action="insert.php" method="POST" enctype="multipart/form-data">
                <label for="flight_number">Numer Lotu:</label>
                <input type="text" id="flight_number" name="flight_number" required>
                
                <label for="origin">Skąd:</label>
                <input type="text" id="origin" name="origin" required>
                
                <label for="destination">Dokąd:</label>
                <input type="text" id="destination" name="destination" required>
                
                <label for="departure_time">Data wylotu:</label>
                <input type="datetime-local" id="departure_time" name="departure_time" required>
                
                <label for="arrival_time">Data przylotu:</label>
                <input type="datetime-local" id="arrival_time" name="arrival_time" required>
                
                <label for="price">Cena (PLN):</label>
                <input type="number" id="price" name="price" step="0.01" required>
                
                <label for="image">Zdjęcie samolotu:</label>
                <input type="file" id="image" name="image" accept="image/*">

                <button type="submit">Dodaj lot</button>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
