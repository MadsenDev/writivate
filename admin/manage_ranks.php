<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle rank updates
    foreach ($_POST['ranks'] as $id => $rank) {
        $stmt = $conn->prepare("UPDATE ranks SET title = ?, rank_number = ?, can_create_guide = ?, can_edit_guide = ?, can_delete_guide = ?, can_manage_categories = ?, can_manage_users = ?, can_manage_tags = ?, can_manage_ranks = ?, can_manage_views = ?, can_manage_comments = ?, can_manage_reports = ?, can_manage_system_settings = ? WHERE id = ?");
        $stmt->bind_param("siiiiiiiiiiiii", $rank['title'], $rank['rank_number'], $rank['can_create_guide'], $rank['can_edit_guide'], $rank['can_delete_guide'], $rank['can_manage_categories'], $rank['can_manage_users'], $rank['can_manage_tags'], $rank['can_manage_ranks'], $rank['can_manage_views'], $rank['can_manage_comments'], $rank['can_manage_reports'], $rank['can_manage_system_settings'], $id);
        $stmt->execute();
    }
}

// Fetch ranks
$result = $conn->query("SELECT * FROM ranks ORDER BY rank_number ASC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Ranks</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
  <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
</head>
<body>
  <?php include 'admin_sidebar.php'; ?>

  <div class="content">
    <h1>Manage Ranks</h1>
    <p>Update rank titles and permissions:</p>

    <form method="post" action="manage_ranks.php">
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Rank Number</th>
            <th>Create Guide</th>
            <th>Edit Guide</th>
            <th>Delete Guide</th>
            <th>Manage Categories</th>
            <th>Manage Users</th>
            <th>Manage Tags</th>
            <th>Manage Ranks</th>
            <th>Manage Views</th>
            <th>Manage Comments</th>
            <th>Manage Reports</th>
            <th>Manage System Settings</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($rank = $result->fetch_assoc()): ?>
            <tr>
              <input type="hidden" name="ranks[<?= $rank['id'] ?>][id]" value="<?= $rank['id'] ?>">
              <td><input type="text" name="ranks[<?= $rank['id'] ?>][title]" value="<?= htmlspecialchars($rank['title']) ?>"></td>
              <td><input type="number" name="ranks[<?= $rank['id'] ?>][rank_number]" value="<?= $rank['rank_number'] ?>"></td>
              <?php
              $permissions = ['can_create_guide', 'can_edit_guide', 'can_delete_guide', 'can_manage_categories', 'can_manage_users', 'can_manage_tags', 'can_manage_ranks', 'can_manage_views', 'can_manage_comments', 'can_manage_reports', 'can_manage_system_settings'];
              foreach ($permissions as $permission):
              ?>
                                <td>
                  <input type="checkbox" name="ranks[<?= $rank['id'] ?>][<?= $permission ?>]" value="1" <?= $rank[$permission] ? 'checked' : '' ?>>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <input type="submit" value="Save Changes">
    </form>
  </div>
</body>
</html>