<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config.php';

if (isset($_POST['submit'])) {
  // Get the theme properties from the form
  $title = $_POST['title'];
  $filename = $_POST['filename'] . ".css";

  // Prepare the CSS content with the properties
  $css = "
body {
    color: {$_POST['body_text_color']};
    background-color: {$_POST['body_bg_color']};
}
a {
    color: {$_POST['a_color']};
}
a:hover {
    color: {$_POST['a_hover_color']};
}
aside {
    background: {$_POST['aside_bg_color']};
}
aside a {
    color: {$_POST['aside_a_color']};
}
aside a:hover {
    background-color: {$_POST['aside_a_hover_bg_color']};
}
.content {
    background-color: {$_POST['content_bg_color']};
}
.recently-viewed-guides {
    border: {$_POST['recently_viewed_guides_border_width']} solid {$_POST['recently_viewed_guides_border_color']};
}
.recently-viewed-guides li {
    border-bottom: {$_POST['recently_viewed_guides_li_border_bottom_width']} solid {$_POST['recently_viewed_guides_li_border_bottom_color']};
}
.recently-viewed-guides span {
    color: {$_POST['recently_viewed_guides_span_color']};
}
.markdown-body pre {
    background: {$_POST['markdown_body_pre_bg_color']};
    border: {$_POST['markdown_body_pre_border_width']} solid {$_POST['markdown_body_pre_border_color']};
}
.updates-list {
    border: {$_POST['updates_list_border_width']} solid {$_POST['updates_list_border_color']};
}
.search-form {
    border: {$_POST['search_form_border_width']} solid {$_POST['search_form_border_color']};
    box-shadow: {$_POST['search_form_box_shadow']};
}
.search-form input {
    color: {$_POST['search_form_input_text_color']};
    background-color: {$_POST['search_form_input_bg_color']};
}
.search-form button {
    background-color: {$_POST['search_form_button_bg_color']};
    color: {$_POST['search_form_button_text_color']};
}
.search-form button:hover {
    background-color: {$_POST['search_form_button_hover_bg_color']};
}
.form-control {
    border: {$_POST['form_control_border_width']} solid {$_POST['form_control_border_color']};
}
.btn-primary {
    background-color: {$_POST['btn_primary_bg_color']};
    color: {$_POST['btn_primary_text_color']};
}
.btn-primary:hover {
    background-color: {$_POST['btn_primary_hover_bg_color']};
}
.profile-rank {
    color: {$_POST['profile_rank_color']};
}
.tag {
    background-color: {$_POST['tag_bg_color']};
}
.tag:hover {
    background-color: {$_POST['tag_hover_bg_color']};
}
.language-selection {
    background-color: {$_POST['language_selection_bg_color']};
    border: {$_POST['language_selection_border_width']} solid {$_POST['language_selection_border_color']};
}
.language-selection select {
    border: {$_POST['language_selection_select_border_width']} solid {$_POST['language_selection_select_border_color']};
}
header {
    background-color: {$_POST['header_bg_color']};
    color: {$_POST['header_text_color']};
}
nav a {
    color: {$_POST['nav_a_color']};
    }
    nav a:hover {
    background-color: {$_POST['nav_a_hover_bg_color']};
    }
    nav ul li ul.submenu {
    background-color: {$_POST['nav_submenu_bg_color']};
    box-shadow: {$_POST['nav_submenu_box_shadow']};
    }
    nav ul li ul.submenu li a:hover {
    background-color: {$_POST['nav_submenu_li_a_hover_bg_color']};
    }
    footer {
    color: {$_POST['footer_text_color']};
    }
    ";

    echo '<pre>' . htmlspecialchars($css) . '</pre>';
    
    // Save the CSS content to the file in /public/themes/
    file_put_contents('../public/themes/' . $filename, $css);
    
    // Insert the theme into the database
    $stmt = $conn->prepare("INSERT INTO themes (title, filename) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $filename);
    $stmt->execute();
    
    header('Location: manage_themes.php?message=Theme added.');
    exit();
    }
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
      <title>Add Theme</title>
      <link rel="icon" type="image/png" href="/public/images/favicon.png">
      <link rel="stylesheet" type="text/css" href="css/admin.css">
      <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
    </head>
    <body>
      <?php include 'admin_sidebar.php'; ?>
      <div class="content">
        <h1>Add Theme</h1>
        <form method="post">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
          </div>
          <div class="form-group">
    <label for="filename">Filename</label>
    <input type="text" name="filename" id="filename" class="form-control" required>
  </div>

  <?php
// Fetch unique group names
$groupQuery = "SELECT DISTINCT group_name FROM theme_options";
$groupResult = $conn->query($groupQuery);

if ($groupResult->num_rows > 0) {
  echo '<form action="add_theme.php" method="post">';
  while ($group = $groupResult->fetch_assoc()) {
    echo '<div class="option-group">';
    echo '<h3>' . $group['group_name'] . '</h3>';
    
    $query = "SELECT id, label, type, name FROM theme_options WHERE group_name = '" . $group['group_name'] . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '<div class="row">';
        echo '  <div class="col-md-6">';
        echo '    <div class="form-group">';
        echo '      <label for="' . $row['name'] . '">' . $row['label'] . '</label>';
        echo '      <input type="' . $row['type'] . '" name="' . $row['name'] . '" id="' . $row['name'] . '" class="form-control" required>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
      }
    }
    
    echo '</div>';
  }
  echo '  <div class="form-group">';
  echo '    <button type="submit" name="submit" class="btn btn-primary">Save Theme</button>';
  echo '  </div>';
  echo '</form>';
}
?>
</form>
</div>
</body>
</html>