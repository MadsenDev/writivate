<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Add Guide from Submission</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
</head>
<body>
<?php include '../config.php'; ?>
<?php include 'admin_sidebar.php'; ?>

<main>
  <div class="content">
    <h1>Add Guide from Submission</h1>
    <?php
    $suggestion_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM suggestions WHERE id = ?");
    $stmt->bind_param('i', $suggestion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $suggestion = $result->fetch_assoc();
    ?>
    <form method="POST" action="save_guide.php">
      <div class="form-group">
        <label for="guide-title">Title:</label>
        <input type="text" id="guide-title" name="title" class="form-control" value="Added from suggestions">
      </div>
      <div class="form-group">
        <label for="guide-category">Category:</label>
        <select id="guide-category" name="category" class="form-control">
          <?php
          $stmt = $conn->prepare("SELECT * FROM categories");
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="tags">Tags (separated by commas):</label>
        <input type="text" id="tags" name="tags" class="form-control">
      </div>
      <div class="form-group">
        <label for="guide-content">Content:</label>
        <textarea id="guide-content" name="content" class="form-control"><?= htmlspecialchars($suggestion['suggestion']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Guide</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script>
      var editor = new SimpleMDE({ element: document.getElementById("guide-content") });
    </script>
  </div>
</main>
</body>
</html>