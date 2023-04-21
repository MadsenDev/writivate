<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
  <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
</head>
<body>
  <?php include 'admin_sidebar.php'; ?>
  <div class="content">
    <h1>Manage Themes <a href="add_theme.php" class="btn btn-primary">Add New</a></h1>
    
    <?php
    if (isset($_GET['message'])) {
      echo '<p class="alert">' . $_GET['message'] . '</p>';
    }
    ?>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Filename</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT id, title, filename FROM themes";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '  <td>' . $row['id'] . '</td>';
            echo '  <td>' . $row['title'] . '</td>';
            echo '  <td>' . $row['filename'] . '</td>';
            echo '  <td>';
            echo '    <a href="delete_theme.php?id=' . $row['id'] . '">Delete</a>';
            echo '  </td>';
            echo '</tr>';
          }
        } else {
          echo '<tr>';
          echo '  <td colspan="4">No themes found.</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>