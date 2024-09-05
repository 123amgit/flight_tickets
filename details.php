<?php
include('db.php');
session_start();

$flight_id = $_GET['id'];
$query = "SELECT * FROM flights WHERE id='$flight_id'";
$result = mysqli_query($conn, $query);
$flight = mysqli_fetch_assoc($result);

if (!$flight) {
    echo "Nie znaleziono lotu.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły Lotu - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <?php include 'menu.php'; ?>

    <main>
        <section>
            <h1>Szczegóły Lotu</h1>
            <?php if ($flight['image']): ?>
                <img src="<?php echo $flight['image']; ?>" alt="Zdjęcie samolotu" style="width:300px;">
            <?php endif; ?>
            <p><strong>Numer Lotu:</strong> <?php echo $flight['flight_number']; ?></p>
            <p><strong>Skąd:</strong> <?php echo $flight['origin']; ?></p>
            <p><strong>Dokąd:</strong> 
                <a href="searchFlights.php?destination=<?php echo urlencode($flight['destination']); ?>">
                    <?php echo $flight['destination']; ?>
                </a>
            </p>
            <p><strong>Data Wylotu:</strong> <?php echo $flight['departure_time']; ?></p>
            <p><strong>Data Przylotu:</strong> <?php echo $flight['arrival_time']; ?></p>
            <p><strong>Cena:</strong> <?php echo $flight['price']; ?> PLN</p>

            <?php if (isset($_SESSION['username'])): ?>
                <div>
                    <button class="book" data-flight-id="<?php echo $flight['id']; ?>">Rezerwuj Lot</button>
                </div>
                <div>
                    <img src="images/heart_empty.png" class="fav" data-flight-id="<?php echo $flight['id']; ?>" alt="Dodaj do ulubionych" title="Dodaj do ulubionych">
                </div>
            <?php else: ?>
                <p><a href="login.php">Zaloguj się</a> aby zarezerwować ten lot lub dodać do ulubionych.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
