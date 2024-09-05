<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: login.php');
    exit();
}

include('db.php');

// Dodawanie nowego lotu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_flight'])) {
    $flight_number = $_POST['flight_number'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    // Sprawdzamy, czy istnieje folder 'uploads', jeśli nie, tworzymy go
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Obsługa przesyłania zdjęcia
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            echo "Błąd podczas przesyłania pliku.";
        }
    }

    // Zapytanie do bazy danych
    $query = "INSERT INTO flights (flight_number, origin, destination, departure_time, arrival_time, price, image) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    // Dodajemy zmienną $image do bind_param
    $stmt->bind_param("sssssss", $flight_number, $origin, $destination, $departure_time, $arrival_time, $price, $image);

    if ($stmt->execute()) {
        echo "Lot został dodany pomyślnie.";
    } else {
        echo "Błąd dodawania lotu: " . $conn->error;
    }
}

// Pobieranie lotów
$query = "SELECT * FROM flights";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj Lotami</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Zarządzaj Lotami</h1>

        <h2>Dodaj Nowy Lot</h2>
        <form action="manageFlights.php" method="POST" enctype="multipart/form-data">
            <label for="flight_number">Numer Lotu:</label>
            <input type="text" id="flight_number" name="flight_number" required>

            <label for="origin">Skąd:</label>
            <input type="text" id="origin" name="origin" required>

            <label for="destination">Dokąd:</label>
            <input type="text" id="destination" name="destination" required>

            <label for="departure_time">Data i Czas Wylotu:</label>
            <input type="datetime-local" id="departure_time" name="departure_time" required>

            <label for="arrival_time">Data i Czas Przylotu:</label>
            <input type="datetime-local" id="arrival_time" name="arrival_time" required>

            <label for="price">Cena:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="image">Zdjęcie:</label>
            <input type="file" id="image" name="image" accept="image/*">

            <button type="submit" name="add_flight">Dodaj Lot</button>
        </form>

        <h2>Lista Lotów</h2>
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
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
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
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Zdjęcie lotu" class="flight-image">
                        <?php else: ?>
                            Brak zdjęcia
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="manageFlights.php?delete_flight=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Czy na pewno chcesz usunąć ten lot?');">Usuń</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
