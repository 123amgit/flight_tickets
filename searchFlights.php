<?php
session_start();
include('db.php');

$origin = $_GET['origin'];
$destination = $_GET['destination'];
$departure_date = $_GET['departure_date'];

$query = "SELECT * FROM flights WHERE origin='$origin' AND destination='$destination' AND DATE(departure_time)='$departure_date'";
$result = mysqli_query($conn, $query);

include('menu.php');
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki wyszukiwania - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section>
            <h1>Loty do <?php echo htmlspecialchars($destination); ?></h1>
            <table>
                <thead>
                    <tr>
                        <th>Numer Lotu</th>
                        <th>Skąd</th>
                        <th>Dokąd</th>
                        <th>Data Wylotu</th>
                        <th>Data Przylotu</th>
                        <th>Cena</th>
                        <th>Szczegóły</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['flight_number']; ?></td>
                            <td><?php echo $row['origin']; ?></td>
                            <td><?php echo $row['destination']; ?></td>
                            <td><?php echo $row['departure_time']; ?></td>
                            <td><?php echo $row['arrival_time']; ?></td>
                            <td><?php echo $row['price']; ?> PLN</td>
                            <td><a href="details.php?id=<?php echo $row['id']; ?>">Zobacz</a></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Brak dostępnych lotów.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
