<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php'; // файл для підключення до бази даних

$user_id = $_SESSION['user_id'];

// Додавання в кошик
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];
    $stmt = $conn->prepare("INSERT INTO cart (user_id, game_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dlc_id'])) {
    $dlc_id = $_POST['dlc_id'];
    $stmt = $conn->prepare("INSERT INTO cart (user_id, dlc_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $dlc_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

// Видалення з кошика
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

// Отримання товарів з кошика
$stmt = $conn->prepare("SELECT c.id, g.title AS game_title, d.title AS dlc_title, g.price AS game_price, d.price AS dlc_price
                        FROM cart c
                        LEFT JOIN game g ON c.game_id = g.id
                        LEFT JOIN dlc d ON c.dlc_id = d.id
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total_price += ($row['game_price'] ?? 0) + ($row['dlc_price'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кошик</title>
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "header.php"; ?>
    <div class="cart">
        <h1>Кошик</h1>
        <?php if (empty($items)): ?>
            <p>Ваш кошик порожній.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['game_title'] ?? $item['dlc_title']); ?> - 
                        $<?php echo htmlspecialchars($item['game_price'] ?? $item['dlc_price']); ?>
                        <a href="cart.php?remove=<?php echo $item['id']; ?>">Видалити</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Загальна вартість: $<?php echo $total_price; ?></p>
            <form method="post" action="purchase.php">
                <button type="submit">Оплатити</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
