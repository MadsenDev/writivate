<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Manage Suggestions</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include '../config.php'; ?>
<?php include 'admin_sidebar.php'; ?>

<main>
  <div class="content">
    <h1>Manage Suggestions</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Suggestion</th>
          <th>Submitted</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $conn->prepare("SELECT * FROM suggestions ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
          echo "<td>" . htmlspecialchars($row['suggestion']) . "</td>";
          echo "<td>" . $row['created_at'] . "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</main>

<?php include '../footer.php'; ?>
</body>
</html>