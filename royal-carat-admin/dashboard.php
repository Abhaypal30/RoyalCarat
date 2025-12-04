<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        /* Page background and font */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center; /* horizontal center */
            align-items: center;     /* vertical center */
        }

        /* Container box */
        .container {
            background-color: white;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 350px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
        }

        p {
            font-size: 18px;
            margin-bottom: 35px;
            color: #555;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            margin: 15px 0;
        }

        ul li a {
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-block;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        ul li a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_logged_in']); ?>!</p>
        <ul>
            <li><a href="add-category.php">Category</a></li>
            <li><a href="add-sub-category.php">Subcategory</a></li>
            <li><a href="add-product.php"> Product</a></li>
             <li><a href="view-inquiry.php">View Inquiry</a></li>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</body>
</html>
