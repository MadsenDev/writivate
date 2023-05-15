<?php
include '../config.php';

if (isset($_POST['add_category'])) {
    
    // Add new category
    $category_name = $_POST['category_name'];
    $parent_id = $_POST['parent_id'] ?? null;

    if ($parent_id === "") {
        $parent_id = NULL;
    }

    $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
    if (!$stmt) {
        exit();
    }

    $bind_result = $stmt->bind_param("si", $category_name, $parent_id);
    if (!$bind_result) {
        exit();
    }

    $execute_result = $stmt->execute();
    if (!$execute_result) {
        exit();
    }

    $stmt->close();
    header('Location: manage_categories.php');
} else {
    header('Location: manage_categories.php');
}
?>