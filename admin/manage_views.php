<?php
session_start();
include '../config.php';

// Fetch all guides with associated view information
$stmt = $conn->prepare("SELECT guides.id AS guide_id, guides.title AS guide_title, COUNT(guide_views.id) AS views FROM guides LEFT JOIN guide_views ON guide_views.guide_id = guides.id GROUP BY guides.id, guides.title ORDER BY guides.id ASC");
$stmt->execute();
$guides = $stmt->get_result();

// Remove single view
if (isset($_GET['remove_view'])) {
  $view_id = $_GET['remove_view'];
  $stmt = $conn->prepare("DELETE FROM guide_views WHERE id = ?");
  $stmt->bind_param("i", $view_id);
  $stmt->execute();
  header("Location: manage_views.php");
  exit();
}

// Remove all views for a specific guide
if (isset($_GET['remove_all_views_for_guide'])) {
  $guide_id = $_GET['remove_all_views_for_guide'];
  $stmt = $conn->prepare("DELETE FROM guide_views WHERE guide_id = ?");
  $stmt->bind_param("i", $guide_id);
  $stmt->execute();
  header("Location: manage_views.php");
  exit();
}

// Remove all views
if (isset($_POST['remove_all_views'])) {
  $conn->query("DELETE FROM guide_views");
  header("Location: manage_views.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Views</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<?php include 'admin_sidebar.php'; ?>

<main>
  <div class="content">
    <h1>Manage Views</h1>

    <?php while ($guide = $guides->fetch_assoc()): ?>
      <div class="guide" data-guide-id="<?= $guide['guide_id'] ?>">
        <h2><?= $guide['guide_title'] ?> (<?= $guide['views'] ?> views)</h2>
        <div class="guide-views"></div>
      </div>
    <?php endwhile; ?>

    <form method="POST">
      <button type="submit" name="remove_all_views">Remove All Views</button>
    </form>
  </div>

  <script>
    $(document).ready(function() {
      $('.guide').on('click', function() {
        var $guide = $(this);
        var guideId = $guide.data('guide-id');
        var $guideViews = $guide.find('.guide-views');

        // Check if the guide views have already been fetched
        if ($guideViews.html()) {
          // Toggle the visibility of the guide views
          $guideViews.slideToggle();
        } else {
          // Fetch the guide views
          $.ajax({
            url: 'get_guide_views.php',
            method: 'GET',
            data: {guide_id: guideId},
            success: function(data) {
              $guideViews.html(data);
              $guideViews.slideToggle();
            }
          });
        }
      });
    });
  </script>

</main>
</body>
</html>