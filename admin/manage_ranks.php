<?php
include '../config.php';
include '../functions.php';

// Fetch all ranks
$stmt = $conn->prepare("SELECT * FROM ranks");
$stmt->execute();
$result = $stmt->get_result();
$ranks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Manage Ranks</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <main>
        <div class="content">
            <h1>Manage Ranks <a href="add_rank.php" class="add-new">Add New</a></h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($ranks as $rank) : ?>
                    <tr>
                        <td><?php echo $rank['id']; ?></td>
                        <td><?php echo $rank['title']; ?></td>
                        <td>
                            <?php
                            $permissions = [];
                            if ($rank['can_create_guide']) $permissions[] = 'Create Guides';
                            if ($rank['can_edit_guide']) $permissions[] = 'Edit Guides';
                            if ($rank['can_delete_guide']) $permissions[] = 'Delete Guides';
                            if ($rank['can_manage_categories']) $permissions[] = 'Manage Categories';
                            if ($rank['can_manage_users']) $permissions[] = 'Manage Users';
                            if ($rank['can_manage_ranks']) $permissions[] = 'Manage Ranks';
                            if ($rank['can_manage_views']) $permissions[] = 'Manage Views';
                            if ($rank['can_manage_system_settings']) $permissions[] = 'Manage System Settings';
                            if ($rank['can_add_translations']) $permissions[] = 'Add Translations';
                            if ($rank['can_delete_translations']) $permissions[] = 'Delete Translations';
                            if ($rank['can_edit_translations']) $permissions[] = 'Edit Translations';
                            if ($rank['can_manage_language']) $permissions[] = 'Manage Language';
                            if ($rank['can_manage_suggestions']) $permissions[] = 'Manage Suggestions';
                            if ($rank['can_change_theme']) $permissions[] = 'Change Theme';
                            if ($rank['can_add_theme']) $permissions[] = 'Add Theme';
                            if ($rank['can_delete_theme']) $permissions[] = 'Delete Theme';

                            echo implode(', ', $permissions);
                            ?>
                        </td>
                        <td>
                            <a href="edit_rank.php?id=<?php echo $rank['id']; ?>">Edit</a> |
                            <a href="delete_rank.php?id=<?php echo $rank['id']; ?>" onclick="return confirm('Are you sure you want to delete this rank?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>