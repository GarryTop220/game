<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php'; // файл для підключення до бази даних

$user_id = $_SESSION['user_id'];

// Отримання історії покупок
$query = "SELECT p.id, p.total_price, p.purchase_date, 
                 GROUP_CONCAT(DISTINCT g.id, ':', g.title SEPARATOR ', ') AS games, 
                 GROUP_CONCAT(DISTINCT d.id, ':', d.title SEPARATOR ', ') AS dlcs
          FROM purchases p
          LEFT JOIN purchase_items pi ON p.id = pi.purchase_id
          LEFT JOIN game g ON pi.game_id = g.id
          LEFT JOIN dlc d ON pi.dlc_id = d.id
          WHERE p.user_id = ?
          GROUP BY p.id
          ORDER BY p.purchase_date DESC";

if (!$stmt = $conn->prepare($query)) {
    die("Помилка підготовки запиту для отримання історії покупок: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
if (!$result = $stmt->get_result()) {
    die("Помилка виконання запиту для отримання історії покупок: " . $stmt->error);
}

$purchases = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Історія покупок</title>
    <link rel="stylesheet" href="../css/wallet.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .purchase-item {
            background-color: #444;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .purchase-item a {
            color: #FF5722;
            text-decoration: none;
        }
        .purchase-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="profile-container">
        <div class="sidebar">
            <h2>Меню</h2>
            <ul>
                <li><a href="profile.php">Інформація про користувача</a></li>
                <li><a href="purchase_history.php">Історія покупок</a></li>
                <li><a href="funds_history.php">Історія поповнень</a></li>
                <li><a href="add_funds.php">Поповнення гаманця</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Історія покупок</h1>
            <?php if (empty($purchases)): ?>
                <p>У вас ще немає покупок.</p>
            <?php else: ?>
                <div>
                    <?php foreach ($purchases as $purchase): ?>
                        <div class="purchase-item">
                            <strong>Дата:</strong> <?php echo htmlspecialchars($purchase['purchase_date']); ?><br>
                            <?php if (!empty($purchase['games'])): ?>
                                <strong>Ігри:</strong><br>
                                <?php 
                                $games = explode(', ', $purchase['games']);
                                foreach ($games as $game) {
                                    if (strpos($game, ':') !== false) {
                                        list($game_id, $game_title) = explode(':', $game);
                                        echo '<a href="game_details.php?id=' . htmlspecialchars($game_id) . '&type=game">' . htmlspecialchars($game_title) . '</a><br>';
                                    }
                                }
                                ?>
                            <?php endif; ?>
                            <?php if (!empty($purchase['dlcs'])): ?>
                                <strong>DLC:</strong><br>
                                <?php 
                                $dlcs = explode(', ', $purchase['dlcs']);
                                foreach ($dlcs as $dlc) {
                                    if (strpos($dlc, ':') !== false) {
                                        list($dlc_id, $dlc_title) = explode(':', $dlc);
                                        echo '<a href="game_details.php?id=' . htmlspecialchars($dlc_id) . '&type=dlc">' . htmlspecialchars($dlc_title) . '</a><br>';
                                    }
                                }
                                ?>
                            <?php endif; ?>
                            <strong>Загальна вартість:</strong> $<?php echo htmlspecialchars($purchase['total_price']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
