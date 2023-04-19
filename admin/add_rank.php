<?php
include '../config.php';
include '../functions.php';

if (isset($_POST['add_rank'])) {
    $title = $_POST['title'];
    $can_create_guide = isset($_POST['can_create_guide']) ? 1 : 0;
    $can_edit_guide = isset($_POST['can_edit_guide']) ? 1 : 0;
    $can_delete_guide = isset($_POST['can_delete_guide']) ? 1 : 0;
    $can_manage_categories = isset($_POST['can_manage_categories']) ? 1 : 0;
    $can_manage_users = isset($_POST['can_manage_users']) ? 1 : 0;
    $can_manage_ranks = isset($_POST['can_manage_ranks']) ? 1 : 0;
    $can_manage_views = isset($_POST['can_manage_views']) ? 1 : 0;
    $can_manage_system_settings = isset($_POST['can_manage_system_settings']) ? 1 : 0;
    $can_add_translations = isset($_POST['can_add_translations']) ? 1 : 0;
    $can_delete_translations = isset($_POST['can_delete_translations']) ? 1 : 0;
    $can_edit_translations = isset($_POST['can_edit_translations']) ? 1 : 0;
    $can_manage_language = isset($_POST['can_manage_language']) ? 1 : 0;
    $can_manage_suggestions = isset($_POST['can_manage_suggestions']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO ranks (title, can_create_guide, can_edit_guide, can_delete_guide, can_manage_categories, can_manage_users, can_manage_ranks, can_manage_views, can_manage_system_settings, can_add_translations, can_delete_translations, can_edit_translations, can_manage_language, can_manage_suggestions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiiiiiiiiii", $title, $can_create_guide, $can_edit_guide, $can_delete_guide, $can_manage_categories, $can_manage_users, $can_manage_ranks, $can_manage_views, $can_manage_system_settings, $can_add_translations, $can_delete_translations, $can_edit_translations, $can_manage_language, $can_manage_suggestions);
    $stmt->execute();

    header('Location: manage_ranks.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Add Rank</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <main>
        <div class="content">
            <h1>Add Rank</h1>
            <form method="POST">
                <div class="form-container">
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="permissions-container">
                        <?php
                        $permissions = [
                            "can_create_guide" => "Can Create Guide",
                            "can_edit_guide" => "Can Edit Guide",
                            "can_delete_guide" => "Can Delete Guide",
                            "can_manage_categories" => "Can Manage Categories",
                            "can_manage_users" => "Can Manage Users",
                            "can_manage_ranks" => "Can Manage Ranks",
                            "can_manage_views" => "Can Manage Views",
                            "can_manage_system_settings" => "Can Manage System Settings",
                            "can_add_translations" => "Can Add Translations",
                            "can_delete_translations" => "Can Delete Translations",
                            "can_edit_translations" => "Can Edit Translations",
                            "can_manage_language" => "Can Manage Language",
                            "can_manage_suggestions" => "Can Manage Suggestions"
                        ];

                        foreach ($permissions as $permission_key => $permission_label) {
                        ?>
                            <label for="<?php echo $permission_key; ?>"><?php echo $permission_label; ?>:</label>
                            <input type="checkbox" id="<?php echo $permission_key; ?>" name="<?php echo $permission_key; ?>">
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <button type="submit" name="add_rank">Add Rank</button>
            </form>
        </div>
    </main>
</body>
</html>