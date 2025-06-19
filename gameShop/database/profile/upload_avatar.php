<?php
session_start();
include '../connect.php';

if (isset($_FILES['avatar']) && isset($_SESSION['user_id'])) {
    $avatar = file_get_contents($_FILES['avatar']['tmp_name']); // No need for addslashes with prepared statements
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE profile SET avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("bi", $avatar, $user_id); // 'b' for blob
    $stmt->send_long_data(0, $avatar);

    if ($stmt->execute()) {
        header("Location: ../../pages/profile.php");
    } else {
        echo "Error updating avatar.";
    }
} else {
    header("Location: ../../pages/profile.php");
}
?>