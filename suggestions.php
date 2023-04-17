<?php
include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'head.php'; ?>
  <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
    <div class="suggestions-container">
    <h1>Suggestions</h1>
    <form method="POST" action="submit_suggestion.php">
      <div class="form-group">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="suggestion">Suggestion:</label>
        <textarea id="suggestion" name="suggestion" class="form-control" rows="6" required></textarea>
      </div>
      <div class="g-recaptcha" data-sitekey="6LdW04glAAAAAA_zSfjQUi9CBrXY5PZedNFdFohF"></div>
      <button type="submit" class="btn btn-primary">Submit Suggestion</button>
    </form>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>