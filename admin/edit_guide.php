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
    <?php
      include '../config.php';
      include '../functions.php';
      include 'admin_sidebar.php';
    ?>

    <main>
      <div class="content">
        <h1>Edit <?php echo htmlspecialchars($content_type_single); ?></h1>
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
          $fullpage = $row['full_page'];
          
          // Fetch categories and generate options
          $stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id ORDER BY parent_id, name");
          $stmt->execute();
          $result = $stmt->get_result();
          $categoriesArray = $result->fetch_all(MYSQLI_ASSOC);
          $options = generateCategoryOptions($categoriesArray, null, "", $category_id);
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
              <option value="">Select a category</option>
              <?php echo $options; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="guide-tags">Tags (comma-separated):</label>
            <?php
              // Fetch tags for the guide
              $stmt = $conn->prepare("SELECT name FROM tags INNER JOIN guide_tags ON tags.id = guide_tags.tag_id WHERE guide_tags.guide_id = ?");
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $tags_result = $stmt->get_result();

              $tags_string = "";
              while ($tag_row = $tags_result->fetch_assoc()) {
                $tags_string .= $tag_row['name'] . ', ';
              }
              $tags_string = rtrim($tags_string, ', ');
            ?>
                        <input type="text" id="guide-tags" name="tags" class="form-control" value="<?php echo $tags_string; ?>">
          </div>
          <!-- Full Page toggle -->
          <div class="form-group">
              <label for="full-page">Full Page:</label>
              <input type="checkbox" id="full-page" name="full_page" <?php echo $fullpage ? 'checked' : ''; ?>>
          </div>
          <div class="form-group">
            <label for="guide-content">Content:</label>
            <textarea id="guide-content" name="content" class="form-control"><?php echo $content; ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Update <?php echo htmlspecialchars($content_type_single); ?></button>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
        <script>
          var inlineAttachmentConfig = {
            uploadUrl: 'upload.php', // the script where images will be uploaded
            uploadFieldName: 'image', // the field name for the file upload
            downloadFieldName: 'uploaded',
            allowedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
            progressText: '![Uploading file...]()',
            urlText: "![file]({filename})",
            errorText: "Error uploading file"
          };

          var simplemde = new SimpleMDE({
            element: document.getElementById('guide-content'),
            spellChecker: false,
            forceSync: true,
            autosave: {
              enabled: true,
              delay: 5000,
              uniqueId: "editor01"
            },
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "side-by-side", "fullscreen", "|", "guide"],
            renderingConfig: {
              singleLineBreaks: false,
              codeSyntaxHighlighting: true,
            },
          });

          inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, inlineAttachmentConfig);
        </script>
      </div>
    </main>
  </body>
</html>
