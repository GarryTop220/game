<?php
session_start();
include '../database/connect.php';

// Перевірка, чи користувач увійшов у систему
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/index.php");
    exit();
}

// Отримання інформації про користувача
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM profile WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/shop.css">
    <style>
        .profile-container {
            display: flex;
            padding: 20px;
        }

        .profile-sidebar {
            width: 25%;
            background-color: #171a21;
            padding: 20px;
            margin-right: 20px;
            border-radius: 10px;
        }

        .profile-content {
            width: 75%;
            background-color: #1b2a3a;
            padding: 20px;
            border-radius: 10px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            margin-right: 20px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .profile-header img:hover {
            transform: scale(1.1);
        }

        .profile-header div {
            display: flex;
            flex-direction: column;
            color: #c7d5e0;
        }

        .profile-header div span {
            margin-bottom: 5px;
            font-size: 18px;
        }

        .menu-item {
            margin-bottom: 20px;
            color: #c7d5e0;
            cursor: pointer;
            transition: color 0.3s;
            font-size: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            background-color: #2b2b2b;
        }

        .menu-item:hover {
            color: #ffffff;
            background-color: #3b3b3b;
        }

        .menu-item.active {
            color: #ffffff;
            font-weight: bold;
            background-color: #0077ff;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .content-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            background-image: linear-gradient(to right, #ff00ff, #0084ff);
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
            animation: gradientAnimation 1s linear infinite;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        @-webkit-keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        .content-section form input[type="password"],
        .content-section form input[type="text"],
        .content-section form input[type="email"],
        .content-section form input[type="date"],
        .content-section form select {
            width: 98%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            background-color: #3b3b3b;
            color: #fff;
            border-radius: 5px;
        }

        .content-section form button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            background-color: #0077ff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .content-section form button:hover {
            background-color: #0055cc;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            const contentSections = document.querySelectorAll('.content-section');

            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    contentSections.forEach(section => section.classList.remove('active'));

                    item.classList.add('active');
                    const section = document.getElementById(item.dataset.section);
                    section.classList.add('active');
                });
            });

            const avatarInput = document.getElementById('avatar-input');
            const avatarImage = document.getElementById('avatar-image');
            avatarImage.addEventListener('click', () => avatarInput.click());
            avatarInput.addEventListener('change', function() {
                document.getElementById('avatar-form').submit();
            });
        });
    </script>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-header">
                <form id="avatar-form" action="../database/profile/upload_avatar.php" method="post" enctype="multipart/form-data">
                    <input type="file" id="avatar-input" name="avatar" style="display:none;">
                    <img src="data:image/jpeg;base64,<?= base64_encode($user['avatar']) ?>" alt="Avatar" id="avatar-image">
                </form>
                <div>
                    <span><?= $user['firstName'] . ' ' . $user['secondName'] ?></span>
                    <span>$<?= $user['balance'] ?></span>
                </div>
            </div>
            <div class="menu-item active" data-section="section-password">Зміна паролю</div>
            <div class="menu-item" data-section="section-personal">Персональні дані</div>
            <!--<div class="menu-item" data-section="section-privacy">Налаштування приватності</div>-->
        </div>
        <div class="profile-content">
            <div id="section-password" class="content-section active">
                <h2>Зміна паролю</h2>
                <form action="../database/profile/change_password.php" method="post">
                    <input type="password" name="current_password" placeholder="Старий пароль" required>
                    <input type="password" name="new_password" placeholder="Новий пароль" required>
                    <input type="password" name="confirm_password" placeholder="Підтвердіть новий пароль" required>
                    <button type="submit">Змінити пароль</button>
                </form>
            </div>
            <div id="section-personal" class="content-section">
                <h2>Персональні дані</h2>
                <form action="../database/profile/update_personal.php" method="post">
                    <input type="text" name="firstName" placeholder="Ім'я" value="<?= $user['firstName'] ?>" required>
                    <input type="text" name="secondName" placeholder="Прізвище" value="<?= $user['secondName'] ?>" required>
                    <input type="text" name="nickname" placeholder="Нікнейм" value="<?= $user['nickname'] ?>" required>
                    <input type="text" name="phone" placeholder="Телефон" value="<?= $user['phone'] ?>" required>
                    <input type="email" name="gmail" placeholder="Gmail" value="<?= $user['gmail'] ?>" required>
                    <input type="date" name="dateOfBirth" placeholder="Дата народження" value="<?= $user['dateOfBirth'] ?>" required>
                    <button type="submit">Оновити дані</button>
                </form>
            </div>
            <div id="section-privacy" class="content-section">
                <h2>Налаштування приватності</h2>
                <form action="../database/profile/update_privacy.php" method="post">
                    <select name="privacy" required>
                        <option value="відкритий" <?= $user['privacy'] == 'відкритий' ? 'selected' : '' ?>>Відкритий</option>
                        <option value="закритий" <?= $user['privacy'] == 'закритий' ? 'selected' : '' ?>>Закритий</option>
                        <option value="відкритий для друзів" <?= $user['privacy'] == 'відкритий для друзів' ? 'selected' : '' ?>>Відкритий для друзів</option>
                    </select>
                    <button type="submit">Зберегти налаштування</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
