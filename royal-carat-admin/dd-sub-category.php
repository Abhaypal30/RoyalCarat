<form action="insert-sub-category.php" method="post">
    <select name="category_id">
        <?php
        include 'db.php';
        $res = $conn->query("SELECT id, category_name FROM category");
        while ($row = $res->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
        }
        ?>
    </select>
    <input type="text" name="product_name" placeholder="Subcategory Name" required><br>
    <button type="submit">Add Subcategory</button>
</form>
