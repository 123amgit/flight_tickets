<?php
include('db.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Administrator') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_number = $_POST['flight_number'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    // Obsługa wrzucania zdjęcia
    $image = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $query = "INSERT INTO flights (flight_number, origin, destination, departure_time, arrival_time, price, image) VALUES ('$flight_number', '$origin', '$destination', '$departure_time', '$arrival_time', '$price', '$image')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: admin.php');
    } else {
        echo "Błąd dodawania lotu: " . mysqli_error($conn);
    }
}
?>
