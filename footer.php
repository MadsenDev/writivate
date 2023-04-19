<?php
// Database connection, replace with your own connection details if needed
include 'config.php';

// Fetch footer_text from the 'settings' table
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'footer_text'");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$footer_text = $row['value'];
?>

<footer>
  <p><?php echo htmlspecialchars($footer_text); ?><br><a href="suggestions.php">Submit Suggestion</a></p>
</footer>