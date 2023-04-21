<?php
include '../config.php';
include '../functions.php';

$rank_id = $_GET['id'] ?? null;

if (!$rank_id) {
    die("Invalid rank ID.");
}

$stmt = $conn->prepare("SELECT * FROM ranks WHERE id = ?");
$stmt->bind_param("i", $rank_id);
$stmt->execute();
$result = $stmt->get_result();
$rank = $result->fetch_assoc();

if (!$rank) {
    die("Rank not found.");
}

if (isset($_POST['edit_rank'])) {
    $title = $_POST['title'];
    $can_create_guide = isset($_POST['can_create_guide']) ? 1 : 0;
    $can_edit_guide = isset($_POST['can_edit_guide']) ? 1 : 0;
    $can_delete_guide = isset($_POST['can_delete_guide']) ? 1 : 0;
    $can_manage_categories = isset($_POST['can_manage_categories']) ? 1 : 0;
    $can_manage_users = isset($_POST['can_manage_users']) ? 1 : 0;
    $can_add_translations = isset($_POST['can_add_translations']) ? 1 : 0;
    $can_delete_translations = isset($_POST['can_delete_translations']) ? 1 : 0;
    $can_edit_translations = isset($_POST['can_edit_translations']) ? 1 : 0;
    $can_manage_language = isset($_POST['can_manage_language']) ? 1 : 0;
    $can_manage_suggestions = isset($_POST['can_manage_suggestions']) ? 1 : 0;
    $can_manage_system_settings = isset($_POST['can_manage_system_settings']) ? 1 : 0;
    $can_change_theme = isset($_POST['can_change_theme']) ? 1 : 0;
    $can_add_theme = isset($_POST['can_add_theme']) ? 1 : 0;
    $can_delete_theme = isset($_POST['can_delete_theme']) ? 1 : 0;

    // Update the prepared statement to include the new permissions
    $stmt = $conn->prepare("UPDATE ranks SET title = ?, can_create_guide = ?, can_edit_guide = ?, can_delete_guide = ?, can_manage_categories = ?, can_manage_users = ?, can_add_translations = ?, can_delete_translations = ?, can_edit_translations = ?, can_manage_language = ?, can_manage_suggestions = ?, can_manage_system_settings = ?, can_change_theme = ?, can_add_theme = ?, can_delete_theme = ? WHERE id = ?");
    $stmt->bind_param("siiiiiiiiiiiiii", $title, $can_create_guide, $can_edit_guide, $can_delete_guide, $can_manage_categories, $can_manage_users, $can_add_translations, $can_delete_translations, $can_edit_translations, $can_manage_language, $can_manage_suggestions, $can_manage_system_settings, $can_change_theme, $can_add_theme, $can_delete_theme, $rank_id);
    $stmt->execute();

    header('Location: manage_ranks.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Edit Rank</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <main>
        <div class="content">
            <h1>Edit Rank</h1>
            <form method="POST">
                <div class="form-container">
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo $rank['title']; ?>" required>
                    </div>
                    <div class="permissions-container">
                        <?php
                        $permissions = [
                            "can_create_guide" => "Can Create Guide",
                            "can_edit_guide" => "Can Edit Guide",
                            "can_delete_guide" => "Can Delete Guide",
                            "can_manage_categories" => "Can Manage Categories",
                            "can_manage_users" => "Can Manage Users",
                            "can_add_translations" => "Can Add Translations",
                            "can_delete_translations" => "Can Delete Translations",
                            "can_edit_translations" => "Can Edit Translations",
                            "can_manage_language" => "Can Manage Language",
                            "can_manage_suggestions" => "Can Manage Suggestions",
                            "can_manage_system_settings" => "Can Manage System Settings",
                            "can_change_theme" => "Can Change Theme",
                            "can_add_theme" => "Can Add Theme",
                            "can_delete_theme" => "Can Delete Theme"
                        ];

                        foreach ($permissions as $permission_key => $permission_label) {
                        ?>
                            <label for="<?php echo $permission_key; ?>"><?php echo $permission_label; ?>:</label>
                            <input type="checkbox" id="<?php echo $permission_key; ?>" name="<?php echo $permission_key; ?>" <?php echo $rank[$permission_key] ? 'checked' : ''; ?>>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <button type="submit" name="edit_rank">Update Rank</button>
            </form>
        </div>
    </main>
</body>
</html>