<!DOCTYPE html>
<html>
  <head>
    <title>Wiki</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
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
    if (file_exists($filepath)) {
      $parsedown = new Parsedown();
      $markdown = file_get_contents($filepath);
      $html = $parsedown->text($markdown);
      echo "<h1>$filename</h1>";
      echo "<div>$html</div>";
    } else {
      echo "<h1>File not found</h1>";
    }
  } else {
    echo "<h1>No file specified</h1>";
  }
?>

  </body>
</html>