<html>
<head>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
footer {
    background: rgba(15, 15, 35, 0.95);
    backdrop-filter: blur(20px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 40px 32px;
    text-align: center;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 80px;
    position: relative;
}

footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    pointer-events: none;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.footer-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.footer-logo {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.footer-logo:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 24px rgba(102, 126, 234, 0.3);
}

.footer-left p {
    font-size: 16px;
    font-weight: 500;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
}

.footer-right {
    display: flex;
    align-items: center;
    gap: 24px;
}

.footer-right a {
    text-decoration: none;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.footer-right a:hover {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
}

.footer-right .social-link {
    font-size: 20px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    transition: all 0.3s ease;
}

.footer-right .social-link:hover {
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-3px) rotate(5deg);
    box-shadow: 0 12px 24px rgba(102, 126, 234, 0.3);
}

.footer-right .social-link:nth-child(3):hover {
    background: rgba(59, 89, 152, 0.3);
    color: #3b5998;
}

.footer-right .social-link:nth-child(4):hover {
    background: rgba(29, 161, 242, 0.3);
    color: #1da1f2;
}

.footer-right .social-link:nth-child(5):hover {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    footer {
        padding: 32px 16px;
    }
    
    .footer-content {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .footer-left {
        flex-direction: column;
        gap: 16px;
    }
    
    .footer-right {
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
    }
    
    .footer-right a {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .footer-right .social-link {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}
</style>
<body>
    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <img src="../photoes/logo.jpg" alt="GameShop Logo" class="footer-logo">
                <p>© GameShop 2024. All rights reserved.</p>
            </div>
            <div class="footer-right">
                <a href="#">About Us</a>
                <a href="#">Support</a>
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>