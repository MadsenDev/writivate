<?php
session_start();
include '../config.php';

// Check if user is logged in and has rank 3
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.php');
  exit();
}
// Fetch the user's rank ID from the database
$user_rank_id = 0;
$stmt = $conn->prepare("SELECT rank_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $user_rank_id = $row['rank_id'];
}

// Get current settings
$stmt = $conn->prepare("SELECT name, value FROM settings");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}

// Set variables with fetched values
$site_title = $settings['site_name'];
$site_description = $settings['site_description'];
$contact_email = $settings['contact_email'];
$footer_text = $settings['footer_text'];
$content_type_plural = $settings['content_type_plural'];
$content_type_single = $settings['content_type_single'];
$registration_enabled = $settings['registration_enabled'];
$show_views = $settings['show_views'];
$enable_suggestions = $settings['enable_suggestions'];

// Handle form submissions
if (isset($_POST['update_settings'])) {
  $site_title = $_POST['site_title'];
  $site_description = $_POST['site_description'];
  $contact_email = $_POST['contact_email'];

  $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'site_name'");
  $stmt->bind_param("s", $site_title);
  $stmt->execute();

  $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'site_description'");
  $stmt->bind_param("s", $site_description);
  $stmt->execute();

  $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'contact_email'");
  $stmt->bind_param("s", $contact_email);
  $stmt->execute();

  // Handle logo upload
  if (!empty($_FILES['logo_url']['name'])) {
    $target_dir = "../public/images/";
    $target_file = $target_dir . basename($_FILES['logo_url']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is actually an image
    $check = getimagesize($_FILES['logo_url']['tmp_name']);
    if ($check === false) {
      die("File is not an image.");
    }

    // Allow only certain file formats
    if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
      die("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES['logo_url']['tmp_name'], $target_file)) {
      die("Sorry, there was an error uploading your file.");
    }

    // Update the logo URL in the database
    $logo_url = "/public/images/" . basename($_FILES['logo_url']['name']);
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'logo_url'");
    $stmt->bind_param("s", $logo_url);
    $stmt->execute();
  }
    // Update footer_text, content_type_plural, and content_type_single in the database
    $footer_text = $_POST['footer_text'];
    $content_type_plural = $_POST['content_type_plural'];
    $content_type_single = $_POST['content_type_single'];
    
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'footer_text'");
    $stmt->bind_param("s", $footer_text);
    $stmt->execute();
    
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'content_type_plural'");
    $stmt->bind_param("s", $content_type_plural);
    $stmt->execute();
    
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'content_type_single'");
    $stmt->bind_param("s", $content_type_single);
    $stmt->execute();
    
    // Update registration_enabled in the database
    $registration_enabled = $_POST['registration_enabled'];
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'registration_enabled'");
    $stmt->bind_param("s", $registration_enabled);
    $stmt->execute();

    // Update theme in the database
    $theme = $_POST['theme'];
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'theme'");
    $stmt->bind_param("s", $theme);
    $stmt->execute();

    // Update show_views in the database
    $show_views = $_POST['show_views'];
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'show_views'");
    $stmt->bind_param("s", $show_views);
    $stmt->execute();
    
    // Update enable_suggestions in the database
    $enable_suggestions = $_POST['enable_suggestions'];
    $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'enable_suggestions'");
    $stmt->bind_param("s", $enable_suggestions);
    $stmt->execute();
    
    // Redirect back to manage_settings.php with a success message
    header('Location: manage_settings.php?message=Settings updated.');
    exit();
  }
  
  ?>
  
  <!DOCTYPE html>
  <html>
  <head>
    <title>Wiki - Manage Settings</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
  </head>
  <body>
  <?php include 'admin_sidebar.php'; ?>
  <main>
    <div class="content">
      <?php
      // If the user doesn't have permission to access this page
      if (!check_permission($user_rank_id, 'can_manage_system_settings')) {
        die("You don't have permission to access this page.");
      }
      ?>
      <h1>Manage Settings</h1>
      <form method="POST" enctype="multipart/form-data">
        <fieldset>
          <legend>General</legend>
          <label for="site_title">Site Title:</label>
          <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>">
  
          <label for="site_description">Site Description:</label>
          <textarea id="site_description" name="site_description"><?php echo htmlspecialchars($site_description); ?></textarea>
        </fieldset>
  
        <fieldset>
          <legend>Branding</legend>
          <label for="logo_url">Logo:</label>
          <img src="../<?php echo htmlspecialchars($settings['logo_url']); ?>" alt="Current logo" id="current-logo" style="max-width: 200px; max-height: 200px; margin-top: 10px;"><br>
          <input type="file" id="logo_url" name="logo_url" accept="image/*">
        </fieldset>

        <fieldset>
        <legend>Theme</legend>
        <label for="theme">Choose Theme:</label>
        <select id="theme" name="theme">
          <?php
            $stmt = $conn->prepare("SELECT id, title, filename FROM themes");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
              $selected = $settings['theme'] === $row['filename'] ? 'selected' : '';
              echo "<option value='{$row['filename']}' {$selected}>{$row['title']}</option>";
            }
          ?>
        </select>
        <a href="manage_themes.php">Manage Themes</a>
      </fieldset>
  
        <fieldset>
          <legend>Content Types</legend>
          <label for="content_type_plural">Content Type Plural:</label>
          <input type="text" id="content_type_plural" name="content_type_plural" value="<?php echo htmlspecialchars($content_type_plural); ?>">
  
          <label for="content_type_single">Content Type Single:</label>
          <input type="text" id="content_type_single" name="content_type_single" value="<?php echo htmlspecialchars($content_type_single); ?>">
        </fieldset>
  
        <fieldset>
        <legend>Contact & Footer</legend>
        <label for="contact_email">Contact Email:</label>
        <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>">

        <label for="footer_text">Footer Text:</label>
        <input type="text" id="footer_text" name="footer_text" value="<?php echo htmlspecialchars($footer_text); ?>">
      </fieldset>

      <fieldset>
      <legend>Additional Features</legend>
      <label for="show_views">Show Views:</label>
      <select id="show_views" name="show_views">
        <option value="1" <?php echo ($settings['show_views'] == 1) ? 'selected' : ''; ?>>Enabled</option>
        <option value="0" <?php echo ($settings['show_views'] == 0) ? 'selected' : ''; ?>>Disabled</option>
      </select>

      <label for="enable_suggestions">Enable Suggestions:</label>
      <select id="enable_suggestions" name="enable_suggestions">
        <option value="1" <?php echo ($settings['enable_suggestions'] == 1) ? 'selected' : ''; ?>>Enabled</option>
        <option value="0" <?php echo ($settings['enable_suggestions'] == 0) ? 'selected' : ''; ?>>Disabled</option>
      </select>

      <label for="registration_enabled">Registration Enabled:</label>
        <select id="registration_enabled" name="registration_enabled">
          <option value="1" <?php echo ($settings['registration_enabled'] == 1) ? 'selected' : ''; ?>>Enabled</option>
          <option value="0" <?php echo ($settings['registration_enabled'] == 0) ? 'selected' : ''; ?>>Disabled</option>
        </select>
    </fieldset>

      <button type="submit" name="update_settings">Update Settings</button>

      <?php
        if (isset($_GET['message'])) {
          echo '<p class="success">' . htmlspecialchars($_GET['message']) . '</p>';
        }
      ?>
    </form>
  </div>
</main>
</body>
</html>