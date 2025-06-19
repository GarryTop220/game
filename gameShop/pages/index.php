<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: shop.php");
    exit();
}

// Get error message if exists
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the error message after displaying
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameShop - Welcome</title>
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

.login-form {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 48px;
    border-radius: 24px;
    text-align: center;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.login-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    pointer-events: none;
}

.login-form > * {
    position: relative;
    z-index: 1;
}

.login-form h2 {
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

.login-form h3 {
    margin: 0 0 32px 0;
    font-size: 18px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
}

.login-form input[type="text"],
.login-form input[type="password"] {
    width: 100%;
    padding: 18px 24px;
    margin-bottom: 20px;
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

.login-form input[type="text"]:focus,
.login-form input[type="password"]:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}

.login-form input[type="text"]::placeholder,
.login-form input[type="password"]::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.login-form input[type="submit"] {
    width: 100%;
    padding: 18px 24px;
    margin-top: 8px;
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

.login-form input[type="submit"]:hover {
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

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin: 15% auto;
    padding: 32px;
    width: 90%;
    max-width: 400px;
    text-align: center;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    position: relative;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(255, 142, 142, 0.1) 100%);
    border-radius: 20px;
    pointer-events: none;
}

.modal-content > * {
    position: relative;
    z-index: 1;
}

.close {
    color: rgba(255, 255, 255, 0.6);
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
}

.close:hover,
.close:focus {
    color: #ff6b6b;
    background: rgba(255, 107, 107, 0.2);
    transform: scale(1.1);
}

.modal-content p {
    color: #ff6b6b;
    font-size: 16px;
    font-weight: 500;
    margin: 20px 0 0 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 16px;
    }
    
    .login-form {
        padding: 32px 24px;
        max-width: 100%;
    }
    
    .login-form h2 {
        font-size: 32px;
    }
    
    .login-form input[type="text"],
    .login-form input[type="password"],
    .login-form input[type="submit"] {
        padding: 16px 20px;
        font-size: 14px;
    }
    
    .modal-content {
        margin: 25% auto;
        padding: 24px;
    }
}

/* Animation for form elements */
.login-form {
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

.login-form input {
    animation: slideInRight 0.6s ease-out;
    animation-fill-mode: both;
}

.login-form input:nth-child(3) { animation-delay: 0.1s; }
.login-form input:nth-child(4) { animation-delay: 0.2s; }
.login-form input:nth-child(5) { animation-delay: 0.3s; }

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
        <div class="login-form">
            <h2>GameShop</h2>
            <h3>Welcome Back</h3>
            <form id="loginForm" method="post" action="../database/login.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Sign In">
            </form>
            <div class="links">
                <a href="registration.php">Don't have an account? Create one!</a>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    </div>

    <script>
        // Get modal elements
        var modal = document.getElementById("errorModal");
        var span = document.getElementsByClassName("close")[0];

        // Show modal if there's an error message
        <?php if ($error_message): ?>
        modal.style.display = "block";
        <?php endif; ?>

        // Close modal when clicking X
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>