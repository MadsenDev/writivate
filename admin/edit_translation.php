<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Edit Translation</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
</head>
<body>
<?php //include 'admin_sidebar.php'; ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../config.php';
include '../functions.php';

if (isset($_GET['translation_id'])) {
    $translation_id = $_GET['translation_id'];
    $stmt = $conn->prepare("SELECT * FROM guide_translations WHERE id = ?");
    $stmt->bind_param("i", $translation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $translation = $result->fetch_assoc();
        $guide_id = $translation['guide_id'];
        $original_guide_stmt = $conn->prepare("SELECT * FROM guides WHERE id = ?");
        $original_guide_stmt->bind_param("i", $guide_id);
        $original_guide_stmt->execute();
        $original_guide_result = $original_guide_stmt->get_result();
        if ($original_guide_result->num_rows > 0) {
            $original_guide = $original_guide_result->fetch_assoc();
        } else {
            echo "No original guide found with the provided ID.";
            exit;
        }
    } else {
        echo "No translation found with the provided ID.";
        exit;
    }
} else {
    echo "No translation ID provided.";
    exit;
}

// Fetch languages and generate options
$languages_stmt = $conn->prepare("SELECT * FROM languages ORDER BY language");
$languages_stmt->execute();
$languages_result = $languages_stmt->get_result();
$languagesArray = $languages_result->fetch_all(MYSQLI_ASSOC);
$options = generateLanguageOptions($languagesArray);
?>
<main>
    <div class="content">
        <h1>Edit Translation</h1>
        <div class="flex-container">
            <div class="flex-item">
                <h3>Original Language Guide: <?php echo htmlspecialchars($original_guide['title']); ?></h3>
                <div class="original-guide">
                    <h4>Original Content:</h4>
                    <div class="original-content"><?php echo nl2br(htmlspecialchars($original_guide['content'])); ?></div>
                </div>
            </div>
            <div class="flex-item">
                <form method="POST" action="update_translation.php">
                    <input type="hidden" name="translation_id" value="<?php echo $translation_id; ?>">
                    <input type="hidden" name="guide_id" value="<?php echo $guide_id; ?>">
                    <div class="form-group">
                        <label for="translation-language">Language:</label>
                        <select id="translation-language" name="language_code" class="form-control">
                            <option value="">Select a language</option>
                            <?php echo $options; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="translation-title">Title:</label>
                        <input type="text" id="translation-title" name="title" class="form-control" value="<?php echo htmlspecialchars($translation['title']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="translation-content">Content:</label>
                        <textarea id="translation-content" name="content" class="form-control"><?php echo htmlspecialchars($translation['content']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="translation-url">URL Slug:</label>
                        <input type="text" id="translation-url" name="url_slug" class="form-control" value="<?php echo htmlspecialchars($translation['url_slug']); ?>">
                    </div>
                        <button type="submit" class="btn btn-primary">Save Translation</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
            <script>
                var editor = new SimpleMDE({ element: document.getElementById("translation-content") });
            </script>

        </main>
    </body>
</html>