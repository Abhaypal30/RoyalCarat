<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}
include 'db.php';

$categories = $conn->query("SELECT * FROM category ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 800px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .active-btn, .inactive-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }

        .active-btn {
            background-color: #27ae60;
        }

        .inactive-btn {
            background-color: #e74c3c;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Category List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = $categories->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                echo "<td>" . ($row['status'] ? 'Active' : 'Inactive') . "</td>";
                echo "<td>
                        <form method='post' action='update-category-status.php' style='margin:0;'>
                            <input type='hidden' name='category_id' value='{$row['id']}'>
                            <input type='hidden' name='new_status' value='" . ($row['status'] ? 0 : 1) . "'>
                            <button type='submit' class='" . ($row['status'] ? "inactive-btn" : "active-btn") . "'>
                                " . ($row['status'] ? "Deactivate" : "Activate") . "
                            </button>
                        </form>
                      </td>";
                echo "</tr>";
                $i++;
            }
            ?>
        </tbody>
    </table>

    <h2>Add Category</h2>
    <form action="insert-category.php" method="post">
        <input type="text" name="category_name" placeholder="Category Name" required>
        <button type="submit">Add Category</button>
    </form>
</div>

</body>
</html>
