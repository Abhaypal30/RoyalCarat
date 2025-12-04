<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id']) && isset($_POST['new_status'])) {
    $category_id = intval($_POST['category_id']);
    $new_status = intval($_POST['new_status']);

    $stmt = $conn->prepare("UPDATE category SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $category_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: add-category.php");
exit();
