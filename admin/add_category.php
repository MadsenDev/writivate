<?php
include '../config.php';

if (isset($_POST['add_category'])) {
    // Add new category
    $category_name = $_POST['category_name'];
    $parent_id = $_POST['parent_id'] ?? null;

    $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
    $stmt->bind_param("si", $category_name, $parent_id);
    $stmt->execute();
    
    header('Location: manage_categories.php');
}
?>