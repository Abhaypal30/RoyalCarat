<?php
include 'db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('Missing image ID');
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT product_image FROM product WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($imageData);
    $stmt->fetch();

    // Send image headers
    header("Content-Type: image/jpeg"); // or use image/png depending on stored type
    echo $imageData;
} else {
    http_response_code(404);
    echo "Image not found.";
}

$stmt->close();
?>
