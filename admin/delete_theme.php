<?php
// Include the database connection
require_once '../config.php';

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $themeId = intval($_GET['id']);

    // Fetch the theme to be deleted
    $query = "SELECT * FROM themes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $themeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $theme = $result->fetch_assoc();
        $filename = $theme['filename'];

        // Delete the theme from the themes table
        $deleteQuery = "DELETE FROM themes WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $themeId);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            // Delete the corresponding CSS file
            $fileToDelete = dirname(__DIR__) . "/public/themes/" . $filename;
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            }
        }
    }

    // Redirect back to the theme management page
    header("Location: manage_themes.php");
    exit();
} else {
    // Redirect back to the theme management page with an error message
    header("Location: manage_themes.php?error=missing_id");
    exit();
}
?>