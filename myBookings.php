<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Pobieranie rezerwacji użytkownika
$query = "
    SELECT bookings.id AS booking_id, flights.flight_number, flights.origin, flights.destination, 
           flights.departure_time, flights.arrival_time, flights.price, bookings.status
    FROM bookings
    JOIN flights ON bookings.flight_id = flights.id
    WHERE bookings.user_id = ?
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
    <title>Moje Rezerwacje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Moje Rezerwacje</h1>
        <table>
            <thead>
                <tr>
                    <th>Numer Lotu</th>
                    <th>Skąd</th>
                    <th>Dokąd</th>
                    <th>Data Wylotu</th>
                    <th>Data Przylotu</th>
                    <th>Cena</th>
                    <th>Status</th>
                    <th>Akcje</th>
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
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <!-- Przycisk usunięcia rezerwacji -->
                            <a href="cancelBooking.php?booking_id=<?php echo $row['booking_id']; ?>"
                               onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?');">
                                Anuluj Rezerwację
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Brak rezerwacji.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
