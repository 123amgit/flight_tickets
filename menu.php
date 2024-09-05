<?php
// Upewnij się, że sesja jest uruchomiona
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="index.php">Strona Główna</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Opcje dostępne dla wszystkich zalogowanych użytkowników -->
            <li><a href="myBookings.php">Moje Rezerwacje</a></li>
            <li><a href="favourites.php">Ulubione</a></li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrator'): ?>
                <!-- Dodatkowe opcje tylko dla Administratora -->
                <li><a href="admin.php">Panel Administratora</a></li>
                <li><a href="manageFlights.php">Zarządzaj Lotami</a></li>
                <li><a href="manageUsers.php">Zarządzaj Użytkownikami</a></li>
            <?php endif; ?>

            <li><a href="logout.php">Wyloguj się</a></li>
        <?php else: ?>
            <!-- Opcje dla niezalogowanych użytkowników -->
            <li><a href="login.php">Zaloguj się</a></li>
            <li><a href="register.php">Zarejestruj się</a></li>
        <?php endif; ?>
    </ul>
</nav>
