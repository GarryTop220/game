<?php
// Підключення до бази даних
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gameshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $secondName = $_POST['secondName'];
    $nickname = $_POST['nickname'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $gmail = $_POST['gmail'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Захист пароля

    // Завантаження аватару
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar = file_get_contents($_FILES['avatar']['tmp_name']);
    } else {
        $avatar = null;
    }

    $stmt = $conn->prepare("INSERT INTO profile (firstName, secondName, nickname, balance, country, avatar, phone, gmail, dateOfBirth, login, password) VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $firstName, $secondName, $nickname, $country, $avatar, $phone, $gmail, $dateOfBirth, $login, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: index.php"); // Перенаправлення на головну сторінку після успішної реєстрації
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
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

.registration-form {
    background-color: #2b2b2b; /* Колір фону форми, схожий на Steam */
    padding-right: 40px;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 20px;
    border-radius: 10px;
    text-align: center;
    width: 40%;
    height: 77.5%;
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

.registration-form h2 {
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

.registration-form h3 {
    margin-bottom: 20px;
}

.registration-form input[type="text"],
.registration-form input[type="password"],
.registration-form input[type="email"],
.registration-form input[type="date"],
.registration-form input[type="file"] {
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

.registration-form input[type="submit"] {
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

.registration-form input[type="submit"]:hover {
    background-color: #0055cc; /* Колір кнопки при наведенні, схожий на Steam */
}
</style>
<body>
    <div class="container">
        <div class="registration-form">
            <h2>Вітаємо в gameShop</h2>
            <h3>Створити акаунт</h3>
            <form id="registrationForm" method="post" action="registration.php" enctype="multipart/form-data">
                <input type="text" name="firstName" placeholder="Ім'я" required>
                <input type="text" name="secondName" placeholder="Прізвище" required>
                <input type="text" name="nickname" placeholder="Нікнейм" required>
                <input type="text" name="country" placeholder="Країна" required>
                <input type="file" name="avatar" accept="image/*" required>
                <input type="text" name="phone" placeholder="Телефон" required>
                <input type="email" name="gmail" placeholder="Електронна пошта" required>
                <input type="date" name="dateOfBirth" required>
                <input type="text" name="login" placeholder="Логін" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <input type="submit" value="Зареєструватися">
            </form>
            <div class="links">
                <!--<a href="#">Забули пароль? Відновіть!</a>-->
                <a href="index.php">Уже зареєстровані? Увійдіть!</a>
                <!--<a href="shop.php">Продовжити як гість</a>-->
            </div>
        </div>
    </div>
</body>
</html>
