<?php
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $privacy = $_POST['privacy'];

    $sql = "UPDATE profile SET privacy = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $privacy, $user_id);

    if ($stmt->execute()) {
        header("Location: ../../pages/profile.php");
    } else {
        echo "Error updating privacy settings.";
    }
}
?>
