<?php
include 'config.php';

// Fetch content_type_plural, content_type_single, and show_views from the 'settings' table
$stmt = $conn->prepare("SELECT name, value FROM settings WHERE name IN ('content_type_plural', 'content_type_single', 'show_views')");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}
$content_type_plural = $settings['content_type_plural'];
$content_type_single = $settings['content_type_single'];
$show_views = $settings['show_views'];

// Fetch the top 5 most viewed content
$stmt = $conn->prepare("SELECT guides.id, guides.title, COUNT(guide_views.guide_id) AS view_count
                        FROM guides
                        LEFT JOIN guide_views ON guides.id = guide_views.guide_id
                        GROUP BY guides.id
                        ORDER BY view_count DESC
                        LIMIT 5");
$stmt->execute();
$most_viewed_content = $stmt->get_result();
?>

<aside>
  <?php include 'search_form.php'; ?>
  <h2>Most Viewed <?php echo htmlspecialchars($content_type_plural); ?></h2>
  <ul>
    <?php while ($content = $most_viewed_content->fetch_assoc()): ?>
      <li>
        <a href="guide.php?id=<?= $content['id'] ?>"><?= $content['title'] ?>
        <?php if ($show_views == '1'): ?>
          (<?= $content['view_count'] ?> views)
        <?php endif; ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</aside>