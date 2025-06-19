<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успішна покупка</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .success-container {
            text-align: center;
            padding: 50px;
            background-color: #333;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 500px;
        }
        .success-container h1 {
            color: #4CAF50;
        }
        .success-container p {
            font-size: 18px;
        }
        .success-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .success-container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="success-container">
        <h1>Покупка успішна!</h1>
        <p>Дякуємо за вашу покупку. Ви можете переглянути придбані товари у своєму профілі.</p>
        <a href="profile.php">Перейти до профілю</a>
    </div>
</body>
</html>
