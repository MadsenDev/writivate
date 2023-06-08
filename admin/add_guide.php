<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Add Guide</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
    <script src="../vendor/inline/inline-attachment.min.js"></script>
    <script src="../vendor/inline/codemirror-4.inline-attachment.min.js"></script>
  </head>
  <body>
    <?php include 'admin_sidebar.php'; ?>
    <?php
      include '../config.php';
      include '../functions.php';
      
      // Fetch categories and generate options
      $stmt = $conn->prepare("SELECT categories.*, parent.name as parent_name FROM categories LEFT JOIN categories AS parent ON categories.parent_id = parent.id ORDER BY parent_id, name");
      $stmt->execute();
      $result = $stmt->get_result();
      $categoriesArray = $result->fetch_all(MYSQLI_ASSOC);
      $options = generateCategoryOptions($categoriesArray);
    ?>
    <main>
      <div class="content">
        <h1>Add <?php echo htmlspecialchars($content_type_single); ?></h1>
        <form method="POST" action="save_guide.php">
          <div class="form-group">
            <label for="guide-title">Title:</label>
            <input type="text" id="guide-title" name="title" class="form-control">
          </div>
          <div class="form-group">
            <label for="guide-category">Category:</label>
            <select id="guide-category" name="category" class="form-control">
              <option value="">Select a category</option>
              <?php echo $options; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="tags">Tags (separated by commas):</label>
            <input type="text" id="tags" name="tags" class="form-control">
          </div>
          <!-- Full Page toggle -->
          <div class="form-group">
            <label for="full-page">Full Page:</label>
            <input type="checkbox" id="full-page" name="full_page">
          </div>
          <div class="form-group">
            <label for="guide-content">Content:</label>
            <textarea id="guide-content" name="content" class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Save <?php echo htmlspecialchars($content_type_single); ?></button>
        </form>
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