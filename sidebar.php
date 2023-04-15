<?php
include 'config.php';

// Fetch the top 5 most viewed guides
$stmt = $conn->prepare("SELECT guides.id, guides.title, COUNT(guide_views.guide_id) AS view_count
                        FROM guides
                        LEFT JOIN guide_views ON guides.id = guide_views.guide_id
                        GROUP BY guides.id
                        ORDER BY view_count DESC
                        LIMIT 5");
$stmt->execute();
$most_viewed_guides = $stmt->get_result();
?>

<aside>
      <?php include 'search_form.php'; ?>
  <h2>Most Viewed Guides</h2>
  <ul>
    <?php while ($guide = $most_viewed_guides->fetch_assoc()): ?>
      <li>
        <a href="/guide.php?id=<?= $guide['id'] ?>"><?= $guide['title'] ?> (<?= $guide['view_count'] ?> views)</a>
      </li>
    <?php endwhile; ?>
  </ul>
</aside>