<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Edit Guide</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link rel="stylesheet" type="text/css" href="styles/header.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
  </head>
  <body>

  <?php include 'header.php'; ?>

<main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
    <h1>Edit Guide</h1>
    <?php
      $dir = 'guides';
      $id = $_GET['id'];
      $filepath = "$dir/$id";
      $content = file_get_contents($filepath);
      $parts = explode('/', $id);
      $filename = end($parts);
      $category = count($parts) > 1 ? $parts[0] : '';
      $title = str_replace('-', ' ', ucfirst(pathinfo($filename, PATHINFO_FILENAME)));
    ?>
    <form method="POST" action="update_guide.php">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <div class="form-group">
        <label for="guide-title">Title:</label>
        <input type="text" id="guide-title" name="title" class="form-control" value="<?php echo $title; ?>">
      </div>
      <div class="form-group">
        <label for="guide-category">Category:</label>
        <select id="guide-category" name="category" class="form-control">
          <?php
            $categories = array('');
            $files = scandir($dir);
            foreach ($files as $file) {
              if ($file != '.' && $file != '..') {
                if (is_dir("$dir/$file")) {
                  array_push($categories, $file);
                }
              }
            }
            foreach ($categories as $cat) {
              $selected = $cat === $category ? 'selected' : '';
              echo "<option value=\"$cat\" $selected>$cat</option>";
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="guide-content">Content:</label>
        <textarea id="guide-content" name="content" class="form-control"><?php echo $content; ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update Guide</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script>
      var editor = new SimpleMDE({ element: document.getElementById("guide-content") });
    </script>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>