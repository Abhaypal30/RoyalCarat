<?php
include 'db.php';

$successMessage = '';
$errorMessage = '';

// Handle form submission for adding product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['category_id'], $_POST['sub_category_id'], $_POST['product_description']) && !empty($_FILES['product_image']['name'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $category_id = (int)$_POST['category_id'];
        $sub_category_id = (int)$_POST['sub_category_id'];
        $product_description = $conn->real_escape_string($_POST['product_description']);

        // Handle image upload
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Auto-create if missing
        }

        $imageName = $_FILES['product_image']['name'];
        $imageTmp = $_FILES['product_image']['tmp_name'];
        $uniqueName = time() . '_' . basename($imageName);
        $imagePath = $uploadDir . $uniqueName;

        if (move_uploaded_file($imageTmp, $imagePath)) {
            $sql = "INSERT INTO product (name, category_id, sub_category_id, product_description, product_image, status)
                    VALUES ('$name', $category_id, $sub_category_id, '$product_description', '$imagePath', 1)";
            if ($conn->query($sql)) {
                $successMessage = "Product added successfully!";
            } else {
                $errorMessage = "Database error: " . $conn->error;
            }
        } else {
            $errorMessage = "Failed to upload image. Check folder permissions.";
        }
    } else {
        $errorMessage = "Please fill in all required fields and select an image.";
    }
}

// Toggle product status
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE product SET status = IF(status = 1, 0, 1) WHERE id = $id");
    header("Location: add-product.php");
    exit();
}

// Fetch products for display
$products = $conn->query("
    SELECT p.*, c.category_name, s.product_name AS subcategory_name 
    FROM product p
    JOIN category c ON p.category_id = c.id
    JOIN sub_category s ON p.sub_category_id = s.id
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 30px;
        }
        .form-container, .table-container {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], textarea, select, input[type="file"] {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .status-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            display: inline-block;
        }
        .active {
            background-color: #2ecc71;
        }
        .inactive {
            background-color: #e74c3c;
        }
        .message-success {
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        .message-error {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        img.product-img {
            width: 70px;
            height: auto;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Product</h2>

    <?php if ($successMessage): ?>
        <div class="message-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="message-error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>

        <select name="category_id" id="categoryDropdown" required>
            <option value="">Select Category</option>
            <?php
            $cat = $conn->query("SELECT id, category_name FROM category WHERE status = 1");
            while ($row = $cat->fetch_assoc()) {
                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['category_name']) . "</option>";
            }
            ?>
        </select>

        <select name="sub_category_id" id="subCategoryDropdown" required>
            <option value="">Select Subcategory</option>
        </select>

        <input type="file" name="product_image" id="productImage" required>
        <p id="imageMessage" style="color: red;"></p>

        <textarea name="product_description" placeholder="Enter Product Description" required></textarea>

        <button type="submit">Add Product</button>
    </form>
</div>

<div class="table-container">
    <h2>All Products</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Image</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $i = 1;
        while ($row = $products->fetch_assoc()) {
            $statusLabel = $row['status'] ? 'Active' : 'Inactive';
            $statusClass = $row['status'] ? 'active' : 'inactive';
            $imageTag = $row['product_image'] && file_exists($row['product_image']) 
                        ? "<img src='{$row['product_image']}' class='product-img'>" 
                        : "No Image";
            echo "<tr>
                <td>{$i}</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['category_name']) . "</td>
                <td>" . htmlspecialchars($row['subcategory_name']) . "</td>
                <td>$imageTag</td>
                <td>" . htmlspecialchars($row['product_description']) . "</td>
                <td><span class='status-btn $statusClass'>$statusLabel</span></td>
                <td><a href='?toggle=1&id={$row['id']}' class='status-btn' style='background:#3498db;'>Toggle</a></td>
            </tr>";
            $i++;
        }
        ?>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#categoryDropdown').on('change', function () {
        var categoryId = $(this).val();
        if (categoryId) {
            $.ajax({
                url: 'fetch-subcategories.php',
                type: 'POST',
                data: {category_id: categoryId},
                success: function (data) {
                    $('#subCategoryDropdown').html(data);
                }
            });
        } else {
            $('#subCategoryDropdown').html('<option value="">Select Subcategory</option>');
        }
    });
</script>

<script>
  document.getElementById('productImage').addEventListener('change', function (event) {
    const file = event.target.files[0];

    if (!file) return;

    // Convert file size to KB
    const fileSizeKB = (file.size / 1024).toFixed(2);

    // Read the image to check dimensions
    const img = new Image();
    img.src = URL.createObjectURL(file);
    img.onload = function () {
      const width = img.width;
      const height = img.height;

      const messageElement = document.getElementById('imageMessage');

      if (width === 411 && height === 412) {
        messageElement.style.color = 'green';
        messageElement.textContent = `Image is valid. Dimensions: ${width}x${height}px, Size: ${fileSizeKB} KB`;
      } else {
        messageElement.style.color = 'red';
        messageElement.textContent = `Invalid image size. Required: 411x412px. Uploaded: ${width}x${height}px, Size: ${fileSizeKB} KB`;
      }

      // Free memory
      URL.revokeObjectURL(img.src);
    };
  });
</script>

</body>
</html>
