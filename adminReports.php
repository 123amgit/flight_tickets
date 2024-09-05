<?php
include('db.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Administrator') {
    header('Location: login.php');
    exit();
}

// Query to get the total number of users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total_users'];

// Query to get the total number of bookings
$totalBookingsQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
$totalBookingsResult = mysqli_query($conn, $totalBookingsQuery);
$totalBookings = mysqli_fetch_assoc($totalBookingsResult)['total_bookings'];

// Query to get the most popular destination
$popularDestinationQuery = "SELECT destination, COUNT(*) AS count FROM bookings JOIN flights ON bookings.flight_id = flights.id GROUP BY destination ORDER BY count DESC LIMIT 1";
$popularDestinationResult = mysqli_query($conn, $popularDestinationQuery);
$popularDestination = mysqli_fetch_assoc($popularDestinationResult)['destination'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporty administratora - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <main>
        <section>
            <h1>Raporty systemu</h1>
            <p><strong>Łączna liczba użytkowników:</strong> <?php echo $totalUsers; ?></p>
            <p><strong>Łączna liczba rezerwacji:</strong> <?php echo $totalBookings; ?></p>
            <p><strong>Najpopularniejsza destynacja:</strong> <?php echo $popularDestination; ?></p>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
