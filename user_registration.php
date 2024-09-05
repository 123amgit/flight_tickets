<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Domyślna rola to 'User'
    $role = 'User';

    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['role'] = $role;
        header('Location: index.php'); // Przekierowanie na stronę główną dla użytkowników
        exit();
    } else {
        echo "Błąd rejestracji: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja Użytkownika - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section>
            <h1>Rejestracja Użytkownika</h1>
            <form action="user_registration.php" method="POST">
                <label for="username">Nazwa użytkownika:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Zarejestruj się</button>
            </form>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
