<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php'; // файл для підключення до бази даних

$user_id = $_SESSION['user_id'];

// Отримання історії поповнень
$query = "SELECT amount, payment_method, timestamp 
          FROM funds_history 
          WHERE user_id = ? 
          ORDER BY timestamp DESC";

if (!$stmt = $conn->prepare($query)) {
    die("Помилка підготовки запиту для отримання історії поповнень: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
if (!$result = $stmt->get_result()) {
    die("Помилка виконання запиту для отримання історії поповнень: " . $stmt->error);
}

$funds = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Історія поповнень</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/wallet.css">
    <style>
        .fund-item {
            background-color: #444;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
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
            <h1>Історія поповнень</h1>
            <?php if (empty($funds)): ?>
                <p>У вас ще немає поповнень.</p>
            <?php else: ?>
                <div>
                    <?php foreach ($funds as $fund): ?>
                        <div class="fund-item">
                            <strong>Сума:</strong> $<?php echo number_format($fund['amount'], 2); ?><br>
                            <strong>Спосіб оплати:</strong> <?php echo htmlspecialchars($fund['payment_method']); ?><br>
                            <strong>Дата:</strong> <?php echo htmlspecialchars($fund['timestamp']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
