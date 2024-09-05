<?php
include('db.php');
session_start();

// Włącz wyświetlanie błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sprawdź, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Formularz został przesłany!<br>"; // Debugowanie

    // Pobranie danych z formularza
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Domyślna rola to 'User'
    $role = 'User';

    // Sprawdź, czy został wprowadzony kod administracyjny
    if (!empty($_POST['admin_code'])) {
        echo "Kod administratora został wprowadzony!<br>"; // Debugowanie
        $admin_code = $_POST['admin_code'];
        
        // Zakładamy, że znasz kod administracyjny i jest to np. "admin2024"
        if ($admin_code === 'admin2024') {
            $role = 'Administrator';
            echo "Użytkownik zostanie utworzony jako Administrator.<br>"; // Debugowanie
        }
    }

    // Przygotowanie zapytania do bazy danych
    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    // Wykonanie zapytania i sprawdzenie wyników
    if ($stmt->execute()) {
        echo "Użytkownik został pomyślnie zarejestrowany!<br>"; // Debugowanie
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['role'] = $role;

        // Przekierowanie w zależności od roli użytkownika
        if ($role === 'Administrator') {
            header('Location: admin.php'); // Przekierowanie do panelu administratora
        } else {
            header('Location: index.php'); // Przekierowanie do strony głównej dla użytkowników
        }
        exit(); // Zakończenie skryptu po przekierowaniu
    } else {
        echo "Błąd rejestracji: " . $conn->error; // Pokaż błąd rejestracji
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section>
            <h1>Rejestracja</h1>
            <form action="registration.php" method="POST">
                <label for="username">Nazwa użytkownika:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required>

                <!-- Ukryte pole roli - domyślnie 'User' -->
                <input type="hidden" name="role" value="User">

                <!-- Opcjonalnie: pole do wprowadzenia kodu administracyjnego -->
                <label for="admin_code">Kod administratora (opcjonalne):</label>
                <input type="text" id="admin_code" name="admin_code">

                <button type="submit">Zarejestruj się</button>
            </form>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
