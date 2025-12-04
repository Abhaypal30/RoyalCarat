<?php
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $sub_category_id = $_POST['sub_category_id'];
    $product_description = $_POST['product_description'];
    $imageData = file_get_contents($_FILES['product_image']['tmp_name']);

    $stmt = $conn->prepare("INSERT INTO product (category_id, sub_category_id, product_image, product_description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $category_id, $sub_category_id, $imageData, $product_description);

    if ($stmt->execute()) {
        $message = "✅ Product added successfully!";
    } else {
        $message = "❌ Error adding product: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center; /* center horizontally */
            align-items: center;     /* center vertically */
        }

        .container {
            background-color: white;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 400px;
            box-sizing: border-box;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px 0;
            font-weight: 600;
            color: #444;
        }

        select, input[type="text"], textarea, input[type="file"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            resize: vertical;
        }

        textarea {
            min-height: 80px;
        }

        button {
            margin-top: 25px;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Product</h2>

    <?php if ($message): ?>
        <p class="message <?php echo strpos($message, '❌') !== false ? 'error' : ''; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            <option value="">Select Category</option>
            <!-- You should populate these options dynamically from your DB -->
            <option value="1">Category 1</option>
            <option value="2">Category 2</option>
        </select>

        <label for="sub_category_id">Subcategory</label>
        <select name="sub_category_id" id="sub_category_id" required>
            <option value="">Select Subcategory</option>
            <!-- Populate dynamically -->
            <option value="1">Subcategory 1</option>
            <option value="2">Subcategory 2</option>
        </select>

        <label for="product_description">Product Description</label>
        <textarea name="product_description" id="product_description" placeholder="Enter product description..." required></textarea>

        <label for="product_image">Product Image</label>
        <input type="file" name="product_image" id="product_image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
