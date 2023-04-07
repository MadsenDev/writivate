<?php
  $dir = 'guides';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $filename = str_replace(' ', '-', strtolower($title)) . '.md';
    $filepath = "$dir/$category/$filename";
    if (file_exists($filepath)) {
      die('Guide with this title already exists');
    }
    if (!file_exists("$dir/$category")) {
      mkdir("$dir/$category");
    }
    file_put_contents($filepath, $content);
    header("Location: guide.php?id=$category/$filename");
    exit();
  }
?>