<!DOCTYPE html>
<html>
  <head>
    <title>Wiki</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1>Wiki</h1>
    <?php
  $dir = 'guides';
  $categories = array();
  $files = scandir($dir);
  foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
      if (is_dir("$dir/$file")) {
        // $file is a directory
        $category = $file;
        $categories[$category] = array();
        $subfiles = scandir("$dir/$category");
        foreach ($subfiles as $subfile) {
          if (strpos($subfile, '.md') !== false) {
            $title = str_replace('.md', '', $subfile);
            $categories[$category][$title] = "$dir/$category/$subfile";
          }
        }
      } elseif (strpos($file, '.md') !== false) {
        // $file is a guide file in the root directory
        $title = str_replace('.md', '', $file);
        $categories[''][$title] = "$dir/$file";
      }
    }
  }
  foreach ($categories as $category => $guides) {
    if ($category != '') {
      echo "<h2>$category</h2>";
    }
    echo "<ul>";
    foreach ($guides as $title => $filepath) {
      $parts = explode('/', $filepath);
      $filename = end($parts);
      echo "<li><a href=\"guide.php?id=$category/$filename\">$title</a></li>";
    }
    echo "</ul>";
  }
?>
    <a href="add_guide.php">Add Guide</a>
  </body>
</html>