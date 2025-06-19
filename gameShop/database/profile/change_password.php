<?php
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Перевірка, чи новий пароль збігається з підтвердженням
    if ($new_password !== $confirm_password) {
        echo "Паролі не збігаються.";
        exit();
    }

    // Отримання поточного паролю з бази даних
    $sql = "SELECT password FROM profile WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Перевірка, чи поточний пароль правильний
    if (!password_verify($current_password, $user['password'])) {
        echo "Старий пароль неправильний.";
        exit();
    }

    // Оновлення паролю
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE profile SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_password_hashed, $user_id);

    if ($stmt->execute()) {
        header("Location: ../../pages/profile.php");
    } else {
        echo "Error updating password.";
    }
}
?>
