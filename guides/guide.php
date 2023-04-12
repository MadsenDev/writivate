<?php
function get_full_category_path($conn, $category_id) {
  $path = "";
  while ($category_id != NULL) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $category = $stmt->get_result()->fetch_assoc();
    $category_name = $category['name'];
    $category_id = $category['parent_id'];
    $path = $category_name . ($path ? " > " . $path : "");
  }
  return $path;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Wiki</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="../public/styles/header.css">
  </head>
  <body>
  <?php
  include '../header.php';
  include '../config.php';
  include '../vendor/parsedown/Parsedown.php';

  // Fetch the user's rank number from the database
$user_rank_number = 0;
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT rank_number FROM users INNER JOIN ranks ON users.rank_id = ranks.id WHERE users.id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_rank_number = $row['rank_number'];
  }
}

  if (!isset($_GET['id'])) {
    die("No guide ID provided.");
  }

  $guide_id = intval($_GET['id']);

  $stmt = $conn->prepare("SELECT guides.*, categories.name as category_name, users.username as creator_username FROM guides INNER JOIN categories ON guides.category_id = categories.id INNER JOIN users ON guides.creator_id = users.id WHERE guides.id = ?");
  $stmt->bind_param("i", $guide_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    die("Guide not found.");
  }

  

  $row = $result->fetch_assoc();
  $header = $row['title'];
  $category_path = get_full_category_path($conn, $row['category_id']);
  $creator_username = $row['creator_username'];
  $content = $row['content'];

  $parsedown = new Parsedown();
  $html = $parsedown->text($content);
  ?>

  <main>
    <?php include '../sidebar.php'; ?>
    <div class="content">
      <h1><?php echo $header;
      if ($user_rank_number >= 2) {
        echo " <a href=\"edit_guide.php?id=$guide_id\" class=\"edit-link\">Edit</a>";
      } ?></h1>
      <p style='margin-top: -10px; font-size: 14px; font-style: italic;'>Created by: <?php echo $creator_username; ?></p>
      <p style='margin-top: -10px; font-size: 14px; font-style: italic;'>Category: <?php echo $category_path; ?></p>
      <div><?php echo $html; ?></div>
    </div>
  </main>
  <?php include '../footer.php'; ?>
  </body>
</html>