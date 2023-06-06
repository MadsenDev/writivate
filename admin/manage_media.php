<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Manage Media</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <style>
      .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        grid-gap: 1em;
      }
      .image-grid img {
        max-width: 100%;
        height: auto;
      }
      .overlay {
        visibility: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
        cursor: pointer;
      }
      .overlay-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
      }
    </style>
  </head>
  <body>
    <?php include 'admin_sidebar.php'; ?>
    <?php
      include '../config.php';
      include '../functions.php';

      // Fetch all media files
      $stmt = $conn->prepare("SELECT * FROM uploads");
      $stmt->execute();
      $result = $stmt->get_result();
      $mediaFiles = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <main>
      <div class="content">
        <h1>Manage Media</h1>
        <div class="image-grid">
          <?php foreach ($mediaFiles as $file): ?>
            <img src="../uploads/<?php echo $file['filename']; ?>" alt="<?php echo $file['name']; ?>" onclick="showOverlay('<?php echo $file['filename']; ?>')">
          <?php endforeach; ?>
        </div>
        <div id="overlay" class="overlay">
          <div class="overlay-content">
            <img id="overlay-image" src="" alt="">
            <form id="overlay-form" method="POST" action="update_media.php">
              <input type="hidden" name="id" id="overlay-id">
              <label for="overlay-name">Name:</label>
              <input type="text" id="overlay-name" name="name">
              <button type="submit">Save</button>
            </form>
            <button onclick="deleteMedia()">Delete</button>
          </div>
        </div>
      </div>
    </main>
    <script>
      function showOverlay(filename) {
        document.getElementById('overlay-image').src = '../uploads/' + filename;
        // Here you should also fetch the ID and name of the file and populate the form fields
        document.getElementById('overlay').style.visibility = 'visible';
      }
      function deleteMedia() {
        // Here you should implement deletion of the media file
      }
    </script>
  </body>
</html>