<!DOCTYPE html>
<html>
<head>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(15, 15, 35, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 16px 32px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 32px;
}

.header-left .nav-link {
    text-decoration: none;
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    position: relative;
    font-weight: 500;
    font-size: 16px;
    padding: 12px 20px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.header-left .nav-link:hover {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
}

.header-left .dropdown {
    position: relative;
}

.header-left .dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: rgba(15, 15, 35, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    min-width: 200px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    z-index: 1001;
    padding: 8px;
    margin-top: 8px;
}

.header-left .dropdown-content a {
    color: rgba(255, 255, 255, 0.8);
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    border-radius: 12px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.header-left .dropdown-content a:hover {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    transform: translateX(4px);
}

.header-left .dropdown:hover .dropdown-content {
    display: block;
    animation: fadeInUp 0.3s ease-out;
}

.header-left .dropdown:hover .nav-link {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
}

.header-right {
    display: flex;
    align-items: center;
    gap: 24px;
}

.icon-link {
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    font-size: 20px;
    padding: 12px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.icon-link:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
}

.user-info {
    position: relative;
    display: flex;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    align-items: center;
    cursor: pointer;
    padding: 12px 20px;
    border-radius: 16px;
    transition: all 0.3s ease;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.user-info:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
}

.user-info .avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    object-fit: cover;
}

.user-info:hover .avatar {
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
}

.user-info .username {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    font-size: 16px;
}

.user-info .balance {
    color: #667eea;
    font-weight: 700;
    font-size: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: rgba(15, 15, 35, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    min-width: 200px;
    margin-top: 8px;
    z-index: 1001;
}

.dropdown-menu a {
    display: block;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.8);
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s ease;
    font-weight: 500;
    margin-bottom: 4px;
}

.dropdown-menu a:hover {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    transform: translateX(4px);
}

.dropdown-menu a:last-child {
    margin-bottom: 0;
    color: #ff6b6b;
}

.dropdown-menu a:last-child:hover {
    background: rgba(255, 107, 107, 0.2);
    color: #ff8a8a;
}

.nav-item:hover .dropdown-menu {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    header {
        padding: 12px 16px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .header-left {
        gap: 16px;
    }
    
    .header-left .nav-link {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .user-info {
        padding: 8px 12px;
    }
    
    .user-info .avatar {
        width: 32px;
        height: 32px;
    }
    
    .user-info .username,
    .user-info .balance {
        font-size: 14px;
    }
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userInfo = document.getElementById('user-info');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        if (userInfo && dropdownMenu) {
            userInfo.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.style.display = 'block';
                    dropdownMenu.style.animation = 'fadeInUp 0.3s ease-out';
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
        }
    });
</script>
<body>
    <?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "gameShop";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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