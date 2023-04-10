    <aside>
      <h2>Categories</h2>
      <ul class="menu">
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

          function printCategory($category, $guides) {
            echo "<li><a href=\"#\">$category</a>";
            if (count($guides) > 0) {
              echo "<ul>";
              foreach ($guides as $title => $filepath) {
                $parts = explode('/', $filepath);
                $filename = end($parts);
                echo "<li><a href=\"guide.php?id=$category/$filename\">$title</a></li>";
              }
              echo "</ul>";
            }
            echo "</li>";
          }

          foreach ($categories as $category => $guides) {
            if ($category != '') {
              printCategory($category, $guides);
            } else {
              foreach ($guides as $title => $filepath) {
                $parts = explode('/', $filepath);
                $filename = end($parts);
                echo "<li><a href=\"guide.php?id=$filename\">$title</a></li>";
              }
            }
          }
        ?>
      </ul>
    </aside>