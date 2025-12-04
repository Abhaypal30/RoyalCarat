<?php
include 'db.php';

if (isset($_POST['category_id'])) {
    $category_id = (int)$_POST['category_id'];

    $stmt = $conn->prepare("SELECT id, product_name FROM sub_category WHERE category_id = ? AND status = 1");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Subcategory</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['product_name']) . '</option>';
    }

    $stmt->close();
}
?>
