<?php
// Database connection
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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar = file_get_contents($_FILES['avatar']['tmp_name']);
    } else {
        $avatar = null;
    }

    $stmt = $conn->prepare("INSERT INTO profile (firstName, secondName, nickname, balance, country, avatar, phone, gmail, dateOfBirth, login, password) VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $firstName, $secondName, $nickname, $country, $avatar, $phone, $gmail, $dateOfBirth, $login, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: index.php");
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
    <title>GameShop - Create Account</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.registration-form {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 48px;
    border-radius: 24px;
    text-align: center;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.registration-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    pointer-events: none;
}

.registration-form > * {
    position: relative;
    z-index: 1;
}

.registration-form h2 {
    font-size: 42px;
    font-weight: 700;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 16px 0;
    font-family: 'Poppins', sans-serif;
}

.registration-form h3 {
    margin: 0 0 32px 0;
    font-size: 18px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.form-grid.single {
    grid-template-columns: 1fr;
}

.registration-form input[type="text"],
.registration-form input[type="password"],
.registration-form input[type="email"],
.registration-form input[type="date"],
.registration-form input[type="file"] {
    width: 100%;
    padding: 18px 24px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    box-sizing: border-box;
}

.registration-form input[type="file"] {
    padding: 16px 20px;
    cursor: pointer;
}

.registration-form input[type="text"]:focus,
.registration-form input[type="password"]:focus,
.registration-form input[type="email"]:focus,
.registration-form input[type="date"]:focus,
.registration-form input[type="file"]:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}

.registration-form input[type="text"]::placeholder,
.registration-form input[type="password"]::placeholder,
.registration-form input[type="email"]::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.registration-form input[type="submit"] {
    width: 100%;
    padding: 18px 24px;
    margin-top: 16px;
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 16px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    box-sizing: border-box;
}

.registration-form input[type="submit"]:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.6);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.links {
    margin-top: 32px;
    text-align: center;
}

.links a {
    color: #667eea;
    text-decoration: none;
    margin: 8px 0;
    display: block;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.links a:hover {
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
    color: #ffffff;
}

/* File input styling */
.registration-form input[type="file"]::file-selector-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    margin-right: 12px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.registration-form input[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 16px;
    }
    
    .registration-form {
        padding: 32px 24px;
        max-width: 100%;
    }
    
    .registration-form h2 {
        font-size: 32px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .registration-form input[type="text"],
    .registration-form input[type="password"],
    .registration-form input[type="email"],
    .registration-form input[type="date"],
    .registration-form input[type="file"],
    .registration-form input[type="submit"] {
        padding: 16px 20px;
        font-size: 14px;
    }
}

/* Animation for form elements */
.registration-form {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.registration-form input {
    animation: slideInRight 0.6s ease-out;
    animation-fill-mode: both;
}

.registration-form input:nth-child(1) { animation-delay: 0.1s; }
.registration-form input:nth-child(2) { animation-delay: 0.15s; }
.registration-form input:nth-child(3) { animation-delay: 0.2s; }
.registration-form input:nth-child(4) { animation-delay: 0.25s; }
.registration-form input:nth-child(5) { animation-delay: 0.3s; }
.registration-form input:nth-child(6) { animation-delay: 0.35s; }
.registration-form input:nth-child(7) { animation-delay: 0.4s; }
.registration-form input:nth-child(8) { animation-delay: 0.45s; }
.registration-form input:nth-child(9) { animation-delay: 0.5s; }
.registration-form input:nth-child(10) { animation-delay: 0.55s; }

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
<body>
    <div class="container">
        <div class="registration-form">
            <h2>GameShop</h2>
            <h3>Create Your Account</h3>
            <form id="registrationForm" method="post" action="registration.php" enctype="multipart/form-data">
                <div class="form-grid">
                    <input type="text" name="firstName" placeholder="First Name" required>
                    <input type="text" name="secondName" placeholder="Last Name" required>
                </div>
                <div class="form-grid">
                    <input type="text" name="nickname" placeholder="Nickname" required>
                    <input type="text" name="country" placeholder="Country" required>
                </div>
                <div class="form-grid single">
                    <input type="file" name="avatar" accept="image/*" required>
                </div>
                <div class="form-grid">
                    <input type="text" name="phone" placeholder="Phone Number" required>
                    <input type="email" name="gmail" placeholder="Email Address" required>
                </div>
                <div class="form-grid single">
                    <input type="date" name="dateOfBirth" required>
                </div>
                <div class="form-grid">
                    <input type="text" name="login" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <input type="submit" value="Create Account">
            </form>
            <div class="links">
                <a href="index.php">Already have an account? Sign in!</a>
            </div>
        </div>
    </div>
</body>
</html>