<?php
session_start();
include('db.php');

// Zmienna dla wyników wyszukiwania
$search_results = null;

// Domyślne wartości dla sortowania i kolejności
$sort_by = 'price'; // Domyślne sortowanie po cenie
$order = 'asc'; // Domyślna kolejność rosnąca

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search_flights'])) {
    // Odbieranie danych z formularza
    $origin = $_GET['origin'] ?? '';
    $destination = $_GET['destination'] ?? '';
    $departure_date = $_GET['departure_date'] ?? '';

    // Sprawdzenie, czy są ustawione zmienne sortowania i kolejności
    if (isset($_GET['sort_by'])) {
        $sort_by = $_GET['sort_by'];
    }

    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    }

    // Zapytanie SQL z filtrowaniem
    $query = "SELECT * FROM flights 
              WHERE origin LIKE ? AND destination LIKE ? AND (DATE(departure_time) = ? OR ? = '') 
              ORDER BY $sort_by $order";

    // Użycie operatora LIKE dla miejsc i równe porównanie dla daty
    $origin = '%' . $origin . '%';
    $destination = '%' . $destination . '%';

    // Przygotowanie zapytania SQL
    $stmt = $conn->prepare($query);
    
    // Wiązanie parametrów - 4 parametry
    $stmt->bind_param("ssss", $origin, $destination, $departure_date, $departure_date);
    $stmt->execute();
    $search_results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona Główna - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Witaj na stronie rezerwacji biletów lotniczych</h1>
        
        <h2>Wyszukaj Lot</h2>
        <form action="index.php" method="GET">
            <label for="origin">Skąd:</label>
            <input type="text" id="origin" name="origin" placeholder="Wpisz miejsce wylotu" value="<?php echo htmlspecialchars($_GET['origin'] ?? ''); ?>">

            <label for="destination">Dokąd:</label>
            <input type="text" id="destination" name="destination" placeholder="Wpisz miejsce docelowe" value="<?php echo htmlspecialchars($_GET['destination'] ?? ''); ?>">

            <label for="departure_date">Data Wylotu:</label>
            <input type="date" id="departure_date" name="departure_date" value="<?php echo htmlspecialchars($_GET['departure_date'] ?? ''); ?>">

            <button type="submit" name="search_flights">Wyszukaj</button>
        </form>

        <?php if ($search_results && $search_results->num_rows > 0): ?>
            <h2>Wyniki Wyszukiwania</h2>
            
            <!-- Formularz do sortowania wyników -->
            <form action="index.php" method="GET">
                <!-- Przekazywanie wartości wyszukiwania w ukrytych polach -->
                <input type="hidden" name="origin" value="<?php echo htmlspecialchars($_GET['origin']); ?>">
                <input type="hidden" name="destination" value="<?php echo htmlspecialchars($_GET['destination']); ?>">
                <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($_GET['departure_date']); ?>">

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
                        <th>Ulubione</th>
                        <th>Rezerwuj</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $search_results->fetch_assoc()): ?>
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
                            <a href="addFavourite.php?flight_id=<?php echo $row['id']; ?>">
                                <img src="obrazki/heart_empty.png" alt="Dodaj do ulubionych" class="heart-icon">
                            </a>
                        </td>
                        <td><a href="bookFlight.php?flight_id=<?php echo $row['id']; ?>">Rezerwuj</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif (isset($_GET['search_flights'])): ?>
            <p>Brak lotów spełniających podane kryteria.</p>
        <?php endif; ?>
    </main>
</body>
</html>
