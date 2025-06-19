<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php'; // файл для підключення до бази даних

$user_id = $_SESSION['user_id'];

// Обробка форми поповнення балансу
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'], $_POST['payment_method'])) {
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    // Оновлення балансу користувача
    $stmt = $conn->prepare("UPDATE profile SET balance = balance + ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $user_id);
    $stmt->execute();

    // Додавання запису до історії поповнень
    $stmt = $conn->prepare("INSERT INTO funds_history (user_id, amount, payment_method) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $amount, $payment_method);
    $stmt->execute();

    header("Location: add_funds.php?success=1");
    exit();
}

// Отримання інформації про користувача
$stmt = $conn->prepare("SELECT balance, MAX(timestamp) AS last_fund_date FROM profile p LEFT JOIN funds_history fh ON p.id = fh.user_id WHERE p.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance, $last_fund_date);
$stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поповнення балансу</title>
    <link rel="stylesheet" href="../css/wallet.css">
    <link rel="stylesheet" href="../css/style.css">
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
            <h1>Поповнення балансу</h1>
            <p>Поточний баланс: $<?php echo number_format($balance, 2); ?></p>
            <p>Останнє поповнення: <?php echo $last_fund_date ?: 'Ніколи'; ?></p>

            <?php if (isset($_GET['success'])): ?>
                <p class="success">Баланс успішно поповнено!</p>
            <?php endif; ?>

            <form method="post" action="add_funds.php">
                <label for="amount">Сума поповнення:</label>
                <select name="amount" id="amount">
                    <option value="5">5$</option>
                    <option value="10">10$</option>
                    <option value="20">20$</option>
                    <option value="40">40$</option>
                    <option value="80">80$</option>
                    <option value="160">160$</option>
                </select>

                <label for="payment_method">Спосіб оплати:</label>
                <select name="payment_method" id="payment_method">
                    <option value="Credit Card">Кредитна картка</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bitcoin">Bitcoin</option>
                </select>

                <button type="submit">Поповнити</button>
            </form>
        </div>
    </div>
</body>
</html>
