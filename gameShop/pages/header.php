<!DOCTYPE html>
<html>
<head>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome for icons -->
</head>
<style>
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #171a21;
    padding: 10px 20px;
}

.header-left .nav-link {
    margin-right: 20px;
    text-decoration: none;
    color: #c7d5e0;
    transition: color 0.3s;
    position: relative;
}

.header-left .nav-link:hover {
    color: #ffffff;
}

.header-left .dropdown-content {
    display: none;
    position: absolute;
    background-color: #171a21;
    border-radius: 10px;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.header-left .dropdown-content a {
    color: #c7d5e0;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.header-left .dropdown-content a:hover {
    color: #ffffff;
}

.header-left .dropdown:hover .dropdown-content {
    display: block;
}

.header-left .dropdown:hover .nav-link {
    color: #ffffff;
}

.header-right {
    display: flex;
    align-items: center;
}

.icon-link {
    margin-right: 20px;
    color: #c7d5e0;
    transition: color 0.3s;
}

.icon-link:hover {
    color: #ffffff;
}

.user-info {
    position: relative;
    display: flex;
    background-color: #3a4553;
    align-items: center;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.user-info:hover {
    background-color: #171a21;
    color: #ffffff;
}

.user-info .avatar {
    width: 32px;
    height: 32px;
    border-radius: 5px;
    border: 2px solid #c7d5e0;
    margin-right: 10px;
    transition: border-color 0.3s;
}

.user-info:hover .avatar {
    border-color: #ffffff;
}

.user-info .username {
    color: #c7d5e0;
    margin-right: 10px;
}

.user-info .balance {
    color: #c7d5e0;
    margin-right: 10px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #171a21;
    border: 1px solid #c7d5e0;
    padding: 10px;
    border-radius: 5px;
}

.dropdown-menu a {
    display: block;
    text-decoration: none;
    color: #c7d5e0;
    margin-bottom: 10px;
    transition: color 0.3s;
}

.dropdown-menu a:hover {
    color: #ffffff;
}

.dropdown-menu a:last-child {
    margin-bottom: 0;
}

.nav-item:hover .dropdown-menu {
    display: block;
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userInfo = document.getElementById('user-info');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        userInfo.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.style.display = 'block';
            } else {
                dropdownMenu.style.display = 'none';
            }
        });

        window.addEventListener('click', function(e) {
            if (!userInfo.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                dropdownMenu.style.display = 'none';
            }
        });
    });
</script>
<body>
    <?php
    $host = "localhost"; // Адреса сервера бази даних
    $username = "root"; // Ім'я користувача бази даних
    $password = ""; // Пароль користувача бази даних
    $database = "gameShop"; // Назва бази даних

    // Підключення до бази даних
    $conn = new mysqli($host, $username, $password, $database);

    // Перевірка з'єднання
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Отримання інформації про користувача з бази даних
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $sql = "SELECT * FROM profile WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $avatarData = $row['avatar'];

            // Збереження інформації про користувача в сесії
            $_SESSION['firstName'] = $row['firstName'];
            $_SESSION['secondName'] = $row['secondName'];
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['balance'] = $row['balance'];
            $_SESSION['country'] = $row['country'];
            $_SESSION['avatar'] = $row['avatar'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['gmail'] = $row['gmail'];
            $_SESSION['dateOfBirth'] = $row['dateOfBirth'];
            $_SESSION['login'] = $row['login'];
            $_SESSION['password'] = $row['password'];
        } else {
            echo "No user found with the specified ID";
        }
    } else {
        echo "No user is logged in.";
    }
    ?>
    <header>
        <div class="header-left">
            <span class="dropdown">
                <a href="shop.php" class="nav-link">STORE</a>
                <div class="dropdown-content">
                    <a href="shop.php">Main Page</a>
                    <a href="games.php">Games</a>
                    <a href="cart.php">Cart</a>
                </div>
            </span>
            <a href="library.php" class="nav-link">LIBRARY</a>
        </div>
        <div class="header-right">
            <a href="#" class="icon-link"><i class="fas fa-bell"></i></a>
            <?php
            if (isset($_SESSION['nickname']) && isset($_SESSION['balance'])) {
                echo '<div class="user-info" id="user-info">';
                echo '<img src="data:image/jpeg;base64,'.base64_encode($avatarData).'" alt="Avatar" class="avatar">';
                echo '<span class="username">' . $_SESSION['nickname'] . '</span>';
                echo '<span class="balance">$' . $_SESSION['balance'] . '</span>';
            }
            ?>
                <div class="dropdown-menu">
                    <a href="profile.php">Profile</a>
                    <a href="add_funds.php">Add Funds</a>
                    <a href="../database/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
</body>
</html>
