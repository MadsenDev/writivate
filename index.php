<?php
  include 'config.php';
  include 'functions.php';
  $site_name = get_setting_value($conn, 'site_name');
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
    <div class="updates section">
    <h2>Updates</h2>
        <?php
          $updates_category_id = get_category_id_by_name($conn, "Updates");
          $newest_updates_guides = get_newest_guides_by_category($conn, $updates_category_id, 5);
          foreach ($newest_updates_guides as $guide) {
            echo "<p><a href=\"guide.php?id={$guide['id']}\">{$guide['title']}</a> <em>({$guide['created_at']})</em></p>";
          }
        ?>
    </div>
    <div class="categories-wrapper">
      <div class="category">
        <h2>Computers</h2>
        <?php
          $computers_category_id = get_category_id_by_name($conn, "Computers");
          $newest_computer_guides = get_newest_guides_by_category($conn, $computers_category_id, 10);
          foreach ($newest_computer_guides as $guide) {
            echo "<p><a href=\"guide.php?id={$guide['id']}\">{$guide['title']}</a></p>";
          }
        ?>
      </div>
      <div class="category">
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