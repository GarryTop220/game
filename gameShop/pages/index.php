<?php
session_start();

// Перевірка чи користувач вже увійшов
if (isset($_SESSION['user_id'])) {
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Відеоігровий магазин</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<style>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-form {
    background-color: #2b2b2b; /* Колір фону форми, схожий на Steam */
    padding-right: 40px;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 20px;
    border-radius: 10px;
    text-align: center;
    width: 40%;
    height: 40%;
}

.login-form h2 {
    font-size: 32px; /* Розмір тексту */
    text-align: center; /* Вирівнювання по центру */
    background-image: linear-gradient(to right, #ff00ff, #0084ff); /* Початковий градієнт */
    color: transparent; /* Зробити текст прозорим */
    background-clip: text; /* Застосувати градієнт до тексту */
    -webkit-background-clip: text; /* Для вебкіт браузерів */
    margin-top: 2%; /* Додано для відступу */
    margin-bottom: 2%;
    animation: gradientAnimation 1s linear infinite; /* Анімація градієнту */
}

@keyframes gradientAnimation {
    0% {
        background-position: 0% 50%; /* Початкова позиція градієнту */
    }
    100% {
        background-position: 100% 50%; /* Кінцева позиція градієнту */
    }
}

@-webkit-keyframes gradientAnimation {
    0% {
        background-position: 0% 50%; /* Початкова позиція градієнту */
    }
    100% {
        background-position: 100% 50%; /* Кінцева позиція градієнту */
    }
}

.login-form h3 {
    margin-bottom: 20px;
}

.login-form input[type="text"],
.login-form input[type="password"] {
    width: 100%;
    padding-left: 1.5%;
    padding-bottom: 1.5%;
    padding-top: 1.5%;
    margin-bottom: 1.5%;
    border: none;
    background-color: #3b3b3b; /* Колір поля вводу, схожий на Steam */
    color: #fff; /* Колір тексту у полях вводу */
    border-radius: 5px;
}

.login-form input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-top: 1%;
    margin-left: 1%;
    border: none;
    background-color: #0077ff; /* Колір кнопки, схожий на Steam */
    color: #fff; /* Колір тексту на кнопці */
    border-radius: 5px;
    cursor: pointer;
}

.login-form input[type="submit"]:hover {
    background-color: #0055cc; /* Колір кнопки при наведенні, схожий на Steam */
}

.links {
    margin-top: 20px;
    text-align: right; /* Вирівнюємо посилання до правого краю */
}

.links a {
    color: #0077ff; /* Колір посилань, схожий на Steam */
    text-decoration: none;
    margin: 2% 1%;
    display: block; /* Робимо посилання блочними, щоб вони були в окремих рядках */
}

.links a:hover {
    text-decoration: underline;
}
/* Стилі для модального вікна */
.modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #2b2b2b;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
</style>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Вітаємо в gameShop</h2>
            <h3>Вхід до акаунту</h3>
            <form id="loginForm" method="post" action="../database/login.php">
                <input type="text" name="username" placeholder="Логін" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <input type="submit" value="Увійти">
            </form>
            <div class="links">
                <!--<a href="#">Забули пароль? Відновіть!</a>-->
                <a href="registration.php">Ще не зареєстровані? Створіть акаунт!</a>
                <!--<a href="../database/quest_login.php">Продовжити як гість</a>-->
            </div>
        </div>
    </div>

    <!-- Модальне вікно -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    </div>

    <script>
        // Отримуємо модальне вікно
        var modal = document.getElementById("errorModal");

        // Отримуємо елемент <span> для закриття модального вікна
        var span = document.getElementsByClassName("close")[0];

        // Показуємо модальне вікно, якщо є повідомлення про помилку
        <?php if ($error_message): ?>
        modal.style.display = "block";
        <?php endif; ?>

        // Коли користувач натискає на <span> (x), закриваємо модальне вікно
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Коли користувач натискає будь-де поза модальним вікном, закриваємо його
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <script src="./javaScript/script.js"></script>
</body>
</html>
