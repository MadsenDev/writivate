<?php
include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo $site_name; ?></title>
  <link rel="icon" type="image/png" href="public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="public/styles/main.css">
  <link rel="stylesheet" type="text/css" href="public/styles/header.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
    <div class="suggestions-container">
      <h1>Success!</h1>
      <p>Your suggestion has been submitted successfully. Thank you for your input.</p>
      <p><a href="suggestions.php">Click here</a> to submit another suggestion or use the navigation menu to browse the website.</p>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>