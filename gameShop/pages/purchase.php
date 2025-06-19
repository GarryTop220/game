<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php'; // файл для підключення до бази даних

$user_id = $_SESSION['user_id'];
$total_price = 0;
$items = [];
$error_message = '';

$stmt = $conn->prepare("SELECT c.id, g.id AS game_id, d.id AS dlc_id, g.price AS game_price, d.price AS dlc_price
                        FROM cart c
                        LEFT JOIN game g ON c.game_id = g.id
                        LEFT JOIN dlc d ON c.dlc_id = d.id
                        WHERE c.user_id = ?");
if (!$stmt) {
    $error_message = "Помилка підготовки запиту для отримання товарів з кошика: " . $conn->error;
} else {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Перевірка, чи вже придбано гру або DLC
        $game_id = $row['game_id'] ?? null;
        $dlc_id = $row['dlc_id'] ?? null;

        $check_stmt = $conn->prepare("SELECT COUNT(*)
                                      FROM purchase_items pi
                                      JOIN purchases p ON pi.purchase_id = p.id
                                      WHERE p.user_id = ? AND (pi.game_id = ? OR pi.dlc_id = ?)");
        if (!$check_stmt) {
            $error_message = "Помилка підготовки запиту для перевірки повторної покупки: " . $conn->error;
            break;
        }
        $check_stmt->bind_param("iii", $user_id, $game_id, $dlc_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            continue; // Пропускаємо, якщо гра або DLC вже були придбані
        }

        $items[] = $row;
        $total_price += ($row['game_price'] ?? 0) + ($row['dlc_price'] ?? 0);
    }
    $stmt->close(); // закриваємо стейтмент після виконання
}

if (empty($error_message) && $total_price > 0) {
    // Перевірка балансу користувача
    $stmt = $conn->prepare("SELECT balance FROM profile WHERE id = ?");
    if (!$stmt) {
        $error_message = "Помилка підготовки запиту для перевірки балансу: " . $conn->error;
    } else {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close(); // закриваємо стейтмент після виконання

        if ($balance >= $total_price) {
            // Початок транзакції
            $conn->begin_transaction();
            try {
                // Зменшення балансу користувача
                $stmt = $conn->prepare("UPDATE profile SET balance = balance - ? WHERE id = ?");
                if (!$stmt) {
                    throw new Exception("Помилка підготовки запиту для зменшення балансу: " . $conn->error);
                }
                $stmt->bind_param("di", $total_price, $user_id);
                if (!$stmt->execute()) {
                    throw new Exception("Помилка виконання запиту для зменшення балансу: " . $stmt->error);
                }
                $stmt->close(); // закриваємо стейтмент після виконання

                // Додавання запису про покупку
                $stmt = $conn->prepare("INSERT INTO purchases (user_id, total_price) VALUES (?, ?)");
                if (!$stmt) {
                    throw new Exception("Помилка підготовки запиту для додавання запису про покупку: " . $conn->error);
                }
                $stmt->bind_param("id", $user_id, $total_price);
                if (!$stmt->execute()) {
                    throw new Exception("Помилка виконання запиту для додавання запису про покупку: " . $stmt->error);
                }
                $purchase_id = $stmt->insert_id;
                $stmt->close(); // закриваємо стейтмент після виконання

                // Додавання записів про придбані ігри та DLC
                $stmt = $conn->prepare("INSERT INTO purchase_items (purchase_id, game_id, dlc_id, price) VALUES (?, ?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Помилка підготовки запиту для додавання записів про придбані ігри та DLC: " . $conn->error);
                }
                foreach ($items as $item) {
                    $game_id = $item['game_id'] ?? null;
                    $dlc_id = $item['dlc_id'] ?? null;
                    $price = ($item['game_price'] ?? 0) + ($item['dlc_price'] ?? 0);
                    $stmt->bind_param("iiid", $purchase_id, $game_id, $dlc_id, $price);
                    if (!$stmt->execute()) {
                        throw new Exception("Помилка виконання запиту для додавання записів про придбані ігри та DLC: " . $stmt->error);
                    }
                }
                $stmt->close(); // закриваємо стейтмент після виконання

                // Очищення кошика
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                if (!$stmt) {
                    throw new Exception("Помилка підготовки запиту для очищення кошика: " . $conn->error);
                }
                $stmt->bind_param("i", $user_id);
                if (!$stmt->execute()) {
                    throw new Exception("Помилка виконання запиту для очищення кошика: " . $stmt->error);
                }
                $stmt->close(); // закриваємо стейтмент після виконання

                // Завершення транзакції
                $conn->commit();
                header("Location: purchase_success.php");
                exit();
            } catch (Exception $e) {
                // Відміна транзакції у разі помилки
                $conn->rollback();
                $error_message = "Помилка під час покупки: " . $e->getMessage();
            }
        } else {
            $error_message = "Недостатньо коштів на балансі.";
        }
    }
} elseif (empty($error_message)) {
    $error_message = "Ваш кошик порожній або ви вже придбали всі товари з нього.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Помилка при покупці</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .error-container {
            text-align: center;
            padding: 50px;
            background-color: #333;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 500px;
        }
        .error-container h1 {
            color: #B22222;
        }
        .error-container p {
            font-size: 18px;
            color: #B22222;
        }
        .error-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #B22222;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .error-container a:hover {
            background-color: #a11c1c;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="error-container">
        <h1>Помилка при покупці</h1>
        <p><?php echo $error_message; ?></p>
        <a href="profile.php">Повернутися до профілю</a>
    </div>
</body>
</html>
