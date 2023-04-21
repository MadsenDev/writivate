<?php
// Database connection, replace with your own connection details if needed
include 'config.php';

// Fetch footer_text, enable_suggestions, and contact_email from the 'settings' table
$stmt = $conn->prepare("SELECT name, value FROM settings WHERE name IN ('footer_text', 'enable_suggestions', 'contact_email')");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}
$footer_text = $settings['footer_text'];
$enable_suggestions = $settings['enable_suggestions'];
$contact_email = $settings['contact_email']; // Add this line to fetch the contact_email setting
?>

<footer>
  <p><?php echo htmlspecialchars($footer_text); ?>
  <p><a href="mailto:<?php echo htmlspecialchars($contact_email); ?>">Contact Us</a>
  <?php if ($enable_suggestions == '1'): ?>
   | <a href="suggestions.php">Submit Suggestion</a></p>
  <?php endif; ?>
  <?php if (!empty($contact_email)): ?> <!-- Add this block to check if the contact_email setting is not empty -->
  <?php endif; ?>
  </p>
</footer>