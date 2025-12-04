<?php
include 'db.php';
$category_id = $_POST['category_id'];
$product_name = $_POST['product_name'];
$stmt = $conn->prepare("INSERT INTO sub_category (category_id, product_name) VALUES (?, ?)");
$stmt->bind_param("is", $category_id, $product_name);
$stmt->execute();
echo "Subcategory added!";
