<?php
include('db.php');
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Administrator') {
    header('Location: login.php');
    exit();
}

$flight_id = $_GET['id'];
$query = "SELECT * FROM flights WHERE id='$flight_id'";
$result = mysqli_query($conn, $query);
$flight = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_number = $_POST['flight_number'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    // Obsługa wrzucania zdjęcia
    $image = $flight['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $update_query = "UPDATE flights SET flight_number='$flight_number', origin='$origin', destination='$destination', departure_time='$departure_time', arrival_time='$arrival_time', price='$price', image='$image' WHERE id='$flight_id'";
    
    if (mysqli_query($conn, $update_query)) {
        header('Location: admin.php');
    } else {
        echo "Błąd edytowania lotu: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj lot - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <main>
        <section>
            <h1>Edytuj lot</h1>
            <form action="editFlight.php?id=<?php echo $flight_id; ?>" method="POST" enctype="multipart/form-data">
                <label for="flight_number">Numer Lotu:</label>
                <input type="text" id="flight_number" name="flight_number" value="<?php echo $flight['flight_number']; ?>" required>
                
                <label for="origin">Skąd:</label>
                <input type="text" id="origin" name="origin" value="<?php echo $flight['origin']; ?>" required>
                
                <label for="destination">Dokąd:</label>
                <input type="text" id="destination" name="destination" value="<?php echo $flight['destination']; ?>" required>
                
                <label for="departure_time">Data wylotu:</label>
                <input type="datetime-local" id="departure_time" name="departure_time" value="<?php echo date('Y-m-d\TH:i', strtotime($flight['departure_time'])); ?>" required>
                
                <label for="arrival_time">Data przylotu:</label>
                <input type="datetime-local" id="arrival_time" name="arrival_time" value="<?php echo date('Y-m-d\TH:i', strtotime($flight['arrival_time'])); ?>" required>
                
                <label for="price">Cena (PLN):</label>
                <input type="number" id="price" name="price" value="<?php echo $flight['price']; ?>" step="0.01" required>
                
                <label for="image">Zdjęcie samolotu:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($flight['image']): ?>
                    <img src="<?php echo $flight['image']; ?>" alt="Zdjęcie samolotu" style="width:150px;">
                <?php endif; ?>

                <button type="submit">Zapisz zmiany</button>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
