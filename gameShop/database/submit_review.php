<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review'];
    $review_language = 'English'; // Можна змінити на іншу мову або отримувати її з форми
    $review_date = date('Y-m-d');

    // Перевірка, чи користувач вже залишив відгук
    $check_review_query = "SELECT COUNT(*) FROM reviews WHERE game_id = ? AND user_id = ?";
    $check_review_stmt = $conn->prepare($check_review_query);
    $check_review_stmt->bind_param("ii", $game_id, $user_id);
    $check_review_stmt->execute();
    $check_review_result = $check_review_stmt->get_result();
    $has_reviewed = $check_review_result->fetch_row()[0] > 0;

    if ($has_reviewed) {
        // Якщо вже є відгук, перенаправити користувача назад
        header("Location: ../pages/game_details.php?id=$game_id&type=game&error=already_reviewed");
        exit();
    }

    // Вставка нового відгуку
    $insert_review_query = "INSERT INTO reviews (game_id, user_id, rating, review_text, review_language, review_date) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_review_stmt = $conn->prepare($insert_review_query);
    if (!$insert_review_stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $insert_review_stmt->bind_param("iissss", $game_id, $user_id, $rating, $review_text, $review_language, $review_date);
    if ($insert_review_stmt->execute()) {
        // Якщо успішно, перенаправити назад до сторінки з деталями гри
        header("Location: game_details.php?id=$game_id&type=game&success=review_added");
    } else {
        // Якщо не успішно, показати повідомлення про помилку
        header("Location: game_details.php?id=$game_id&type=game&error=review_failed");
    }
}
?>
