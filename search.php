<?php
include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');

$query = isset($_GET['query']) ? $_GET['query'] : '';
$search_results = search_guides($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'head.php'; ?>
</head>
<body>
  <?php include 'header.php'; ?>
  <main>
    <?php include 'sidebar.php'; ?>
    <div class="content">
      <h1>Search Results</h1>

      <div class="search-results">
        <?php foreach ($search_results as $result): ?>
          <div class="search-result">
            <h2><a href="guide.php?id=<?php echo $result['id']; ?>"><?php echo $result['title']; ?></a></h2>
            <!-- Display additional information if necessary -->
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>