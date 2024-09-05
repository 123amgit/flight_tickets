<?php
include('db.php');
session_start();

// Włącz wyświetlanie błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Zapytanie do bazy danych, aby znaleźć użytkownika
    $query = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Debugowanie: wyświetl rolę użytkownika
            echo "Rola użytkownika: " . $user['role'] . "<br>";

            // Przekierowanie w zależności od roli użytkownika
            if ($user['role'] === 'Administrator') {
                header('Location: admin.php'); // Przekierowanie do panelu administratora
            } else {
                header('Location: index.php'); // Przekierowanie do strony głównej dla użytkowników
            }
            exit(); // Zakończenie skryptu po przekierowaniu
        } else {
            echo "Błędne hasło.";
        }
    } else {
        echo "Nie znaleziono użytkownika.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section>
            <h1>Logowanie</h1>
            <form action="login.php" method="POST">
                <label for="username_or_email">Nazwa użytkownika lub Email:</label>
                <input type="text" id="username_or_email" name="username_or_email" required>
                
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Zaloguj się</button>
            </form>

            <p>Nie masz konta? <a href="user_registration.php">Zarejestruj się jako użytkownik</a></p>
            <p>Jesteś administratorem? <a href="admin_registration.php">Zarejestruj się jako administrator</a></p>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
