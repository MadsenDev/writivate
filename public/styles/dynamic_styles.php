<?php
header("Content-type: text/css");

include '../config.php';

$stmt = $conn->prepare("SELECT name, value FROM settings");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}

$primary_color = $settings['primary_color'];
$secondary_color = $settings['secondary_color'];
?>

body {
  background-color: <?php echo $primary_color; ?>;
}

a {
  color: <?php echo $secondary_color; ?>;
}