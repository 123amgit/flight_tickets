<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: login.php');
    exit();
}

include('db.php');

// Usuwanie użytkownika
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $query = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "Użytkownik został usunięty.";
    } else {
        echo "Błąd usuwania użytkownika: " . $conn->error;
    }
}

// Pobranie listy użytkowników
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj Użytkownikami - Rezerwacja Biletów Lotniczych</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <main>
        <h1>Zarządzaj Użytkownikami</h1>

        <h2>Lista Użytkowników</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa Użytkownika</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <a href="manageUsers.php?delete_user=<?php echo $row['id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">Usuń</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
