<?php
session_start();
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $firstName = $_POST['firstName'];
    $secondName = $_POST['secondName'];
    $nickname = $_POST['nickname'];
    $phone = $_POST['phone'];
    $gmail = $_POST['gmail'];
    $dateOfBirth = $_POST['dateOfBirth'];

    $sql = "UPDATE profile SET firstName = ?, secondName = ?, nickname = ?, phone = ?, gmail = ?, dateOfBirth = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstName, $secondName, $nickname, $phone, $gmail, $dateOfBirth, $user_id);

    if ($stmt->execute()) {
        header("Location: ../../pages/profile.php");
    } else {
        echo "Error updating personal information.";
    }
}
?>
