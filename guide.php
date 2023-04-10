<!DOCTYPE html>
<html>
  <head>
    <title>Wiki</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" type="text/css" href="styles/header.css">
  </head>
  <body>
  <?php
  session_start();
  include 'header.php';
  include 'config.php';
  
  // Fetch the user's rank from the database
  $user_rank = '';
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT rank FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $user_rank = $row['rank'];
    }
  }
  ?>

  <main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
  <?php
  require_once 'parsedown/Parsedown.php';

  $dir = 'guides';
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $parts = explode('/', $id);
    $filename = end($parts); // get the last part of the path
    array_pop($parts); // remove the filename from the path
    $subdir = implode('/', $parts); // join the remaining parts back together as a subdirectory

    if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
      die('Invalid file path');
    }

    $filepath = "$dir/$subdir/$filename";
    $filename = basename($filepath);
    $title = ucwords(str_replace('-', ' ', str_replace('.md', '', $filename)));
    if (file_exists($filepath)) {
      $parsedown = new Parsedown();
      $markdown = file_get_contents($filepath);
      $html = $parsedown->text($markdown);
      echo "<h1>$title";
      if ($user_rank == 'add_edit') {
        echo " <a href=\"edit_guide.php?id=$id\" class=\"edit-link\">Edit</a>";
      }
      echo "</h1>";
      echo "<div>$html</div>";
    } else {
      echo "<h1>File not found</h1>";
    }
  } else {
    echo "<h1>No file specified</h1>";
  }
?>
  </div>
</main>
<?php include 'footer.php'; ?>
  </body>
</html>