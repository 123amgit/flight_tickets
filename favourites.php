<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Domyślne wartości dla sortowania i kolejności
$sort_by = 'price'; // Domyślne sortowanie po cenie
$order = 'asc'; // Domyślna kolejność rosnąca

// Sprawdzenie, czy są ustawione zmienne sortowania i kolejności
if (isset($_GET['sort_by'])) {
    $sort_by = $_GET['sort_by'];
}

if (isset($_GET['order'])) {
    $order = $_GET['order'];
}

// Pobierz ulubione loty użytkownika z filtrowaniem i sortowaniem
$query = "
    SELECT flights.flight_number, flights.origin, flights.destination, 
           flights.departure_time, flights.arrival_time, flights.price, flights.image, favourites.flight_id
    FROM favourites
    JOIN flights ON favourites.flight_id = flights.id
    WHERE favourites.user_id = ?
    ORDER BY $sort_by $order
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulubione Loty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Ulubione Loty</h1>

        <!-- Formularz do sortowania -->
        <form action="favourites.php" method="GET">
            <label for="sort_by">Sortuj według:</label>
            <select name="sort_by" id="sort_by">
                <option value="price" <?php if ($sort_by == 'price') echo 'selected'; ?>>Cena</option>
                <option value="departure_time" <?php if ($sort_by == 'departure_time') echo 'selected'; ?>>Czas wylotu</option>
                <option value="arrival_time" <?php if ($sort_by == 'arrival_time') echo 'selected'; ?>>Czas przylotu</option>
            </select>

            <label for="order">Kolejność:</label>
            <select name="order" id="order">
                <option value="asc" <?php if ($order == 'asc') echo 'selected'; ?>>Rosnąco</option>
                <option value="desc" <?php if ($order == 'desc') echo 'selected'; ?>>Malejąco</option>
            </select>

            <button type="submit">Sortuj</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Numer Lotu</th>
                    <th>Skąd</th>
                    <th>Dokąd</th>
                    <th>Data Wylotu</th>
                    <th>Data Przylotu</th>
                    <th>Cena</th>
                    <th>Zdjęcie</th>
                    <th>Usuń z Ulubionych</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['flight_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['origin']); ?></td>
                        <td><?php echo htmlspecialchars($row['destination']); ?></td>
                        <td><?php echo htmlspecialchars($row['departure_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['arrival_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?> PLN</td>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Zdjęcie lotu" class="flight-image">
                            <?php else: ?>
                                Brak zdjęcia
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="removeFavourite.php?flight_id=<?php echo $row['flight_id']; ?>">
                                <img src="obrazki/heart_filled.png" alt="Usuń z ulubionych" class="heart-icon">
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Brak ulubionych lotów.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
