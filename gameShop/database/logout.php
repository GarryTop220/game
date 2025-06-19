<?php
session_start();
session_unset(); // Очистити всі змінні сесії
session_destroy(); // Знищити сесію

header("Location: ../pages/index.php"); // Перенаправлення на сторінку логіну
exit();
?>
