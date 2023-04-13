<!DOCTYPE html>
<html>
  <head>
    <title>Wiki</title>
    <link rel="icon" type="image/png" href="public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/header.css">
  </head>
  <body>
  <?php include 'header.php'; ?>
  <?php include 'config.php'; ?>
  <?php include 'functions.php'; ?>

<main>
  <?php include 'sidebar.php'; ?>
  <div class="content">
    <div style="display: flex; justify-content: space-between;">
      <div style="width: 48%;">
        <h2>Computers</h2>
        <?php
          $computers_category_id = get_category_id_by_name($conn, "Computers");
          $newest_computer_guides = get_newest_guides_by_category($conn, $computers_category_id, 10);
          foreach ($newest_computer_guides as $guide) {
            echo "<p><a href=\"guide.php?id={$guide['id']}\">{$guide['title']}</a></p>";
          }
        ?>
      </div>
      <div style="width: 48%;">
        <h2>Mobile Phones</h2>
        <?php
          $mobile_phones_category_id = get_category_id_by_name($conn, "Mobile Phones");
          $newest_mobile_phone_guides = get_newest_guides_by_category($conn, $mobile_phones_category_id, 10);
          foreach ($newest_mobile_phone_guides as $guide) {
            echo "<p><a href=\"guide.php?id={$guide['id']}\">{$guide['title']}</a></p>";
          }
        ?>
      </div>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
  </body>
</html>