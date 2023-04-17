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
    $rank_number = $_POST['rank_number'];
    $can_create_guide = isset($_POST['can_create_guide']) ? 1 : 0;
    $can_edit_guide = isset($_POST['can_edit_guide']) ? 1 : 0;
    $can_delete_guide = isset($_POST['can_delete_guide']) ? 1 : 0;
    $can_manage_categories = isset($_POST['can_manage_categories']) ? 1 : 0;
    $can_manage_users = isset($_POST['can_manage_users']) ? 1 : 0;
    $can_manage_tags = isset($_POST['can_manage_tags']) ? 1 : 0;
    $can_manage_ranks = isset($_POST['can_manage_ranks']) ? 1 : 0;
    $can_manage_views = isset($_POST['can_manage_views']) ? 1 : 0;
    $can_manage_comments = isset($_POST['can_manage_comments']) ? 1 : 0;
    $can_manage_reports = isset($_POST['can_manage_reports']) ? 1 : 0;
    $can_manage_system_settings = isset($_POST['can_manage_system_settings']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE ranks SET title = ?, rank_number = ?, can_create_guide = ?, can_edit_guide = ?, can_delete_guide = ?, can_manage_categories = ?, can_manage_users = ?, can_manage_tags = ?, can_manage_ranks = ?, can_manage_views = ?, can_manage_comments = ?, can_manage_reports = ?, can_manage_system_settings = ? WHERE id = ?");
    $stmt->bind_param("siiiiiiiiiiiii", $title, $rank_number, $can_create_guide, $can_edit_guide, $can_delete_guide, $can_manage_categories, $can_manage_users, $can_manage_tags, $can_manage_ranks, $can_manage_views, $can_manage_comments, $can_manage_reports, $can_manage_system_settings, $rank_id);
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
                        
                        <label for="rank_number">Rank Number:</label>
                        <input type="number" id="rank_number" name="rank_number" value="<?php echo $rank['rank_number']; ?>" required>
                    </div>
                    <div class="permissions-container">
                        <?php
                        $permissions = [
                            "can_create_guide" => "Can Create Guide",
                            "can_edit_guide" => "Can Edit Guide",
                            "can_delete_guide" => "Can Delete Guide",
                            "can_manage_categories" => "Can Manage Categories",
                            "can_manage_users" => "Can Manage Users",
                            "can_manage_tags" => "Can Manage Tags",
                            "can_manage_ranks" => "Can Manage Ranks",
                            "can_manage_views" => "Can Manage Views",
                            "can_manage_comments" => "Can Manage Comments",
                            "can_manage_reports" => "Can Manage Reports",
                            "can_manage_system_settings" => "Can Manage System Settings"
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