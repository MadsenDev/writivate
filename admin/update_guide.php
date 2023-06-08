<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    $updater_id = $_SESSION['user_id'];
    
    // Get the full page checkbox value, convert to 0 or 1
    $full_page = isset($_POST['full_page']) ? 1 : 0;

    // Update the guide in the database
    // Notice the added 'full_page' field in the SQL statement
    $stmt = $conn->prepare("UPDATE guides SET title = ?, category_id = ?, content = ?, full_page = ? WHERE id = ?");
    if ($stmt === false) {
        echo "Error: (" . $conn->errno . ") " . $conn->error;
        exit;
    }
    // 'i' has been added to bind_param to accommodate the new 'full_page' field
    $stmt->bind_param("sisii", $title, $category_id, $content, $full_page, $id);
    if (!$stmt->execute()) {
        echo "Error: (" . $stmt->errno . ") " . $stmt->error;
        exit;
    }

    // Insert the update information into the guide_updates table
    $stmt = $conn->prepare("INSERT INTO guide_updates (guide_id, updater_id, updated_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $id, $updater_id);
    $stmt->execute();

    // Remove existing guide_tags relationships
    $stmt = $conn->prepare("DELETE FROM guide_tags WHERE guide_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Insert new tags
    $tags = explode(",", $_POST['tags']);
    foreach ($tags as $tag) {
        $tag = trim($tag);
        if (!empty($tag)) {
            // Check if the tag already exists
            $stmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
            $stmt->bind_param("s", $tag);
            $stmt->execute();
            $tag_result = $stmt->get_result();

            // If the tag doesn't exist, insert it into the tags table
            if ($tag_result->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
                $stmt->bind_param("s", $tag);
                $stmt->execute();
                $tag_id = $stmt->insert_id;
            } else {
                $tag_row = $tag_result->fetch_assoc();
                $tag_id = $tag_row['id'];
            }

            // Insert the relationship into the guide_tags table
            $stmt = $conn->prepare("INSERT INTO guide_tags (guide_id, tag_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $id, $tag_id);
            $stmt->execute();
        }
    }

    header("Location: manage_guides.php");
    exit();
}
?>