<?php
  $dir = 'guides';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    
    $old_filepath = "$dir/$id";
    $filename = str_replace(' ', '-', strtolower($title)) . '.md';
    $new_filepath = "$dir/$category/$filename";
    
    if (!file_exists("$dir/$category")) {
      mkdir("$dir/$category");
    }
    
    // Delete old file if the title or category has changed
    if ($old_filepath !== $new_filepath) {
      unlink($old_filepath);
    }
    
    file_put_contents($new_filepath, $content);
    header("Location: guide.php?id=$category/$filename");
    exit();
  }
?>