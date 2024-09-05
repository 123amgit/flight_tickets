<?php
session_start();
include('db.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$flight_id = $_GET['id'];

// Pobieranie szczegółów lotu
$query = "SELECT * FROM flights WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$flight_result = $stmt->get_result();
$flight = $flight_result->fetch_assoc();

// Pobieranie komentarzy
$query = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.flight_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$comments_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły Lotu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Szczegóły Lotu: <?php echo htmlspecialchars($flight['flight_number']); ?></h1>
        <p><strong>Skąd:</strong> <?php echo htmlspecialchars($flight['origin']); ?></p>
        <p><strong>Dokąd:</strong> <?php echo htmlspecialchars($flight['destination']); ?></p>
        <p><strong>Data Wylotu:</strong> <?php echo htmlspecialchars($flight['departure_time']); ?></p>
        <p><strong>Data Przylotu:</strong> <?php echo htmlspecialchars($flight['arrival_time']); ?></p>
        <p><strong>Cena:</strong> <?php echo htmlspecialchars($flight['price']); ?> PLN</p>

        <?php if ($flight['image']): ?>
            <img src="<?php echo htmlspecialchars($flight['image']); ?>" alt="Zdjęcie lotu" class="flight-image">
        <?php endif; ?>

        <h2>Komentarze</h2>
        <form action="addComment.php" method="POST">
            <label for="comment">Dodaj Komentarz:</label>
            <textarea id="comment" name="comment" required></textarea>
            <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
            <button type="submit">Dodaj Komentarz</button>
        </form>

        <h3>Lista Komentarzy</h3>
        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p>
        <?php endwhile; ?>
    </main>
</body>
</html>
