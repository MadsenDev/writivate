<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Edit Guide</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/0UzjzpxpwPRzKzggrD17l5ovvhF+kcJ6Y6F5/z" crossorigin="anonymous">
  </head>
  <body>
  <?php include '../config.php'; ?>
  <?php include 'admin_sidebar.php'; ?>

<main>
  <div class="content">
    <h1>Edit Guide</h1>
    <?php
      $id = $_GET['id'];
      $stmt = $conn->prepare("SELECT * FROM guides WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $title = $row['title'];
      $category_id = $row['category_id'];
      $content = $row['content'];
    ?>
    <form method="POST" action="update_guide.php">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <div class="form-group">
        <label for="guide-title">Title:</label>
        <input type="text" id="guide-title" name="title" class="form-control" value="<?php echo $title; ?>">
      </div>
      <div class="form-group">
        <label for="guide-category">Category:</label>
        <select id="guide-category" name="category_id" class="form-control">
  <?php
    include 'config.php';
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $selected = $row['id'] == $category_id ? 'selected' : '';
      echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
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
</body>
</html>