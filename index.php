<!DOCTYPE html>
<html>
  <head>
    <title>Wiki</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" type="text/css" href="styles/header.css">
  </head>
  <body>
  <?php include 'header.php'; ?>

<main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
    <?php
    require_once 'parsedown/Parsedown.php';

    $dir = 'guides';
    $filepath = "$dir/home.md";
    $parsedown = new Parsedown();
    $markdown = file_get_contents($filepath);
    $html = $parsedown->text($markdown);
    echo "<div class=\"markdown-body\">$html</div>";
    ?>
  </div>
</main>

<?php include 'footer.php'; ?>
  </body>
</html>