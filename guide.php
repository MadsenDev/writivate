<?php
include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="vendor/prism/prism.css">
</head>
<body>
<?php
include 'header.php';
include 'vendor/parsedown/Parsedown.php';

if (isset($_GET['id'])) {
    $guide_id = intval($_GET['id']);

    // Insert a new view for the guide
    $view_id = insertGuideView($guide_id, $_SESSION['user_id']);

    // Start a timer when the page is visited
    $start_time = microtime(true);

    // Register a shutdown function to update the view duration when the page is closed or refreshed
    register_shutdown_function(function() use ($view_id, $start_time) {
        $duration = microtime(true) - $start_time;
        updateGuideViewDuration($view_id, $duration);
    });
}

if (!isset($_GET['id'])) {
    die("No guide ID provided.");
}

$guide_id = intval($_GET['id']);

// Fetch available translations for the specific guide
$translation_stmt = $conn->prepare("SELECT languages.language, languages.language_code FROM guide_translations INNER JOIN languages ON guide_translations.language = languages.language_code WHERE guide_translations.guide_id = ?");
$translation_stmt->bind_param("i", $guide_id);
$translation_stmt->execute();
$translations = $translation_stmt->get_result();

$language_code = $_GET['language'] ?? '';

$stmt = $conn->prepare(
    "SELECT guides.*, categories.name as category_name, users.username as creator_username,
    gt.title as translated_title, gt.content as translated_content
    FROM guides
    INNER JOIN categories ON guides.category_id = categories.id
    INNER JOIN users ON guides.creator_id = users.id
    LEFT JOIN guide_translations gt ON gt.guide_id = guides.id AND gt.language = ?
    WHERE guides.id = ?"
);
$stmt->bind_param("si", $language_code, $guide_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.0 404 Not Found');
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}

$row = $result->fetch_assoc();

$creator = $row['creator_username'];
$timestamp = $row['created_at'];
$category = get_full_category_path($conn, $row['category_id']);

if ($language_code && $row['translated_title'] && $row['translated_content']) {
    $title = $row['translated_title'];
    $content = $row['translated_content'];
} else {
    $title = $row['title'];
    $content = $row['content'];
}

$parsedown = new Parsedown();
$html = $parsedown->text($content);

$tag_stmt = $conn->prepare("SELECT tags.* FROM tags INNER JOIN guide_tags ON tags.id = guide_tags.tag_id WHERE guide_tags.guide_id = ?");
$tag_stmt->bind_param("i", $guide_id);
$tag_stmt->execute();
$tags = $tag_stmt->get_result();
?>
<main>
    <?php if (!isset($row['full_page']) || !$row['full_page']) {
        include 'sidebar.php';
    } ?>
    <div class="content">
<div class="header-container">
<h1><?php echo $title; ?></h1>
    <div class="language-selection hide-on-print">
        <label for="language-select">Language:</label>
        <select id="language-select">
            <option value="" selected>Original</option>
            <?php while ($translation = $translations->fetch_assoc()): ?>
                <option value="<?php echo $translation['language_code']; ?>" <?php echo ($language_code == $translation['language_code'] ? 'selected' : ''); ?>><?php echo htmlspecialchars($translation['language']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
</div>

<p style='margin-top: -10px; font-size: 14px; font-style: italic;'>Created by: <?php echo $creator; ?></p>
<p style='margin-top: -10px; font-size: 14px; font-style: italic;'>Category: <?php echo $category; ?></p>
<div><?php echo $html; ?></div>
<div class="guide-tags hide-on-print">
  <div class="tags-container">
    <?php while ($tag = $tags->fetch_assoc()): ?>
      <span class="tag"><?php echo htmlspecialchars($tag['name']); ?></span>
    <?php endwhile; ?>
  </div>
</div>
<div class="updates-list hide-on-print">
    <h4>Update History:</h4>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT guide_updates.*, users.username as updater_username FROM guide_updates INNER JOIN users ON guide_updates.updater_id = users.id WHERE guide_updates.guide_id = ? ORDER BY guide_updates.updated_at DESC");
        $stmt->bind_param("i", $guide_id);
        $stmt->execute();
        $updates_result = $stmt->get_result();
        while ($update_row = $updates_result->fetch_assoc()) : ?>
            <li>
                <?php
                $update_date = date("F j, Y, g:i a", strtotime($update_row['updated_at']));
                echo "Updated on {$update_date} by {$update_row['updater_username']}";
                ?>
            </li>
        <?php endwhile; ?>
        <li>
            <?php
            $created_date = date("F j, Y, g:i a", strtotime($timestamp));
            echo "Created on {$created_date} by {$creator}";
            ?>
        </li>
    </ul>
</div>
<a href="#" id="printContent" class="hide-on-print">Print Guide</a>
</div>
</main>
<?php include 'footer.php'; ?>
<script src="vendor/prism/prism.js"></script>
<script>
document.getElementById("printContent").addEventListener("click", function () {
    const printWindow = window.open("", "_blank");
    const content = document.querySelector(".content").cloneNode(true);
    const hiddenElements = content.querySelectorAll(".hide-on-print");

    hiddenElements.forEach(element => {
        element.style.display = "none";
    });

    printWindow.document.write("<html><head><title>Print Content</title></head><body>");
    printWindow.document.write(content.innerHTML);
    printWindow.document.write("</body></html>");
    printWindow.document.close();
    printWindow.print();

    printWindow.addEventListener("afterprint", function() {
        printWindow.close();
    });
});

document.getElementById("language-select").addEventListener("change", function () {
    const language = this.value;
    const urlParams = new URLSearchParams(window.location.search);
    if (language) {
        urlParams.set("language", language);
    } else {
        urlParams.delete("language");
    }
    window.location.search = urlParams.toString();
});
</script>
</body>
</html>