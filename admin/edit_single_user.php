<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Edit User</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
  </head>
  <body>
    <?php include 'admin_sidebar.php'; ?>

    <?php
      include '../config.php';

      if (!isset($_GET['id'])) {
        die('No user specified.');
      }

      $user_id = $_GET['id'];

      $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 0) {
        die('User not found.');
      }

      $user = $result->fetch_assoc();

      if (isset($_POST['edit_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $rank_id = $_POST['rank_id'];

        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, rank_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $username, $email, $rank_id, $user_id);
        $stmt->execute();

        header('Location: manage_users.php');
      }
    ?>

    <main>
      <div class="content">
        <h1>Edit User</h1>

        <form method="POST">
          <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
          <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
          <select name="rank_id">
            <?php
              $stmt = $conn->prepare("SELECT * FROM ranks");
              $stmt->execute();
              $result = $stmt->get_result();

              while ($row = $result->fetch_assoc()) {
                $selected = $row['id'] == $user['rank_id'] ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['title']}</option>";
              }
            ?>
          </select>

          <button type="submit" name="edit_user">Save</button>
        </form>
      </div>
    </main>
  </body>
</html>