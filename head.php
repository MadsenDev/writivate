<?php
include '/config.php';
// Fetch theme from the database
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'theme'");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $theme = $row['value'];
} else {
  $theme = "default"; // Default theme in case it's not found in the database
}
?>

<title><?php echo $site_name; ?></title>
<link rel="icon" type="image/png" href="/public/images/favicon.png">
<link rel="stylesheet" type="text/css" href="/public/styles/main.css">
<link rel="stylesheet" type="text/css" href="/public/themes/<?php echo htmlspecialchars($theme); ?>.css">