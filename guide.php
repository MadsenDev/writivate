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
    $subdir = implode('/', $parts); // join the remaining parts back together as a subdirectory, allowing for subcategories

    if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
      die('Invalid file path');
    }

    $filepath = "$dir/$subdir/$filename";
    $filename = basename($filepath);
    $title = ucwords(str_replace('-', ' ', str_replace('.md', '', $filename)));

    // Get guide and creator information
    $stmt = $conn->prepare("SELECT guides.*, users.username as creator, categories.name as category_name, categories.parent_id FROM guides INNER JOIN users ON guides.creator_id = users.id LEFT JOIN categories ON guides.category_id = categories.id WHERE file_path = ?");
    $stmt->bind_param("s", $filepath);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $creator = $row['creator'];
    $last_updater = $row['last_updater'];

    // Get all updates and updater information
    $stmt = $conn->prepare("SELECT guide_updates.*, users.username as updater FROM guide_updates INNER JOIN users ON guide_updates.updater_id = users.id WHERE guide_id = ? ORDER BY updated_at DESC");
    $stmt->bind_param("i", $row['id']);
    $stmt->execute();
    $updates = $stmt->get_result();

    if (file_exists($filepath)) {
      $parsedown = new Parsedown();
      $markdown = file_get_contents($filepath);
      $html = $parsedown->text($markdown);
      echo "<h1>$title";
      if ($user_rank == 'add_edit') {
        echo " <a href=\"edit_guide.php?id=$id\" class=\"edit-link\">Edit</a>";
      }
      echo "</h1>";
      echo "<p style='margin-top: -10px; font-size: 14px; font-style: italic;'>Created by: $creator</p>";
      if (isset($row['category_name'])) {
        $category_name = $row['category_name'];
        echo "<p style='margin-top: -10px; font-size: 14px; font-style: italic;'>Category: $category_name";
        if (isset($row['parent_id'])) {
        $parent_id = $row['parent_id'];
        while ($parent_id !== null) {
        $stmt = $conn->prepare("SELECT name, parent_id FROM categories WHERE id = ?");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $category_name = $row['name'];
        $parent_id = $row['parent_id'];
        echo " > $category_name";
        }
        }
        echo "</p>";
        }
        echo "<div>$html</div>";
                // List all updates
                echo "<h3>Updates:</h3>";
                echo "<ul>";
                while ($update = $updates->fetch_assoc()) {
                  $updater = $update['updater'];
                  $updated_at = $update['updated_at'];
                  echo "<li>Updated by $updater on $updated_at</li>";
                }
                echo "</ul>";
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
