<?php
session_start();
include 'db.php';

// Toggle subcategory status if requested
if (isset($_GET['toggle_id'])) {
    $id = (int)$_GET['toggle_id'];
    $conn->query("UPDATE sub_category SET status = IF(status = 1, 0, 1) WHERE id = $id");
    header("Location: add-sub-category.php");
    exit();
}

// Fetch subcategories with category names
$subQuery = $conn->query("
    SELECT s.id, s.product_name, s.status, c.category_name 
    FROM sub_category s 
    JOIN category c ON s.category_id = c.id
    ORDER BY s.id ASC
");

// Fetch only active categories for the dropdown
$categories = $conn->query("SELECT id, category_name FROM category WHERE status = 1");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Subcategory</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }

        .active {
            background-color: green;
        }

        .inactive {
            background-color: red;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        select, input[type="text"] {
            padding: 12px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Subcategory List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Subcategory Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Toggle</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($subQuery->num_rows > 0): 
                $i = 1;
                while ($row = $subQuery->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td><?= $row['status'] ? 'Active' : 'Inactive' ?></td>
                    <td>
                        <a class="status-btn <?= $row['status'] ? 'active' : 'inactive' ?>" 
                           href="?toggle_id=<?= $row['id'] ?>">
                           <?= $row['status'] ? 'Deactivate' : 'Activate' ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align: center;">No subcategories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Add Subcategory</h2>
    <form action="insert-sub-category.php" method="post">
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['category_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="product_name" placeholder="Subcategory Name" required>
        <button type="submit">Add Subcategory</button>
    </form>
</div>

</body>
</html>
