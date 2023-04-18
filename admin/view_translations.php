<!DOCTYPE html>
<html>
<head>
  <title>Wiki - View Translations</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include 'admin_sidebar.php'; ?>

<?php
include '../config.php';

$guide_id = $_GET['guide_id'];

// Fetch the translations for the guide
$stmt = $conn->prepare("SELECT gt.id, gt.title, l.language FROM guide_translations gt INNER JOIN languages l ON gt.language = l.id WHERE gt.guide_id = ?");
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
  <div class="content">
    <h1>Translations for Guide #<?php echo $guide_id; ?> <a href="add_translation.php?guide_id=<?php echo $guide_id; ?>">Add Translation</a></h1>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Language</th>
          <th>Title</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
          $translation_id = $row['id'];
          $language = $row['language'];
          $title = $row['title'];

          echo "<tr>";
          echo "<td>$translation_id</td>";
          echo "<td>$language</td>";
          echo "<td>$title</td>";
          echo "<td><a href=\"edit_translation.php?id=$translation_id\">Edit</a> | <a href=\"view_translations.php?delete_translation=1&translation_id=$translation_id&guide_id=$guide_id\" onclick=\"return confirm('Are you sure you want to delete this translation?')\">Delete</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>