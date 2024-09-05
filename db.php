<?php
// Ustawienia połączenia z bazą danych
$servername = "localhost";      // Nazwa hosta (zazwyczaj 'localhost')
$username = "root";             // Nazwa użytkownika bazy danych
$password = "";                 // Hasło użytkownika bazy danych
$database = "plane_booking";    // Nazwa bazy danych

// Nawiązanie połączenia
$conn = new mysqli($servername, $username, $password, $database);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Ustawienie zestawu znaków na utf8
$conn->set_charset("utf8");
?>
