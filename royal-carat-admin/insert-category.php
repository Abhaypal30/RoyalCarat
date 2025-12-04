<?php
include 'db.php';
$category_name = $_POST['category_name'];
$stmt = $conn->prepare("INSERT INTO category (category_name) VALUES (?)");
$stmt->bind_param("s", $category_name);
$stmt->execute();
echo "Category added!";
