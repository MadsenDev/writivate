<?php
include '../config.php';
include '../functions.php';

// Fetch all ranks
$stmt = $conn->prepare("SELECT * FROM ranks ORDER BY rank_number");
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
            <h1>Manage Ranks</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Rank Number</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($ranks as $rank) : ?>
                    <tr>
                        <td><?php echo $rank['id']; ?></td>
                        <td><?php echo $rank['title']; ?></td>
                        <td><?php echo $rank['rank_number']; ?></td>
                        <td>
                            <?php
                            $permissions = [];
                            if ($rank['can_create_guide']) $permissions[] = 'Create Guides';
                            if ($rank['can_edit_guide']) $permissions[] = 'Edit Guides';
                            if ($rank['can_delete_guide']) $permissions[] = 'Delete Guides';
                            if ($rank['can_manage_categories']) $permissions[] = 'Manage Categories';
                            if ($rank['can_manage_users']) $permissions[] = 'Manage Users';
                            if ($rank['can_manage_tags']) $permissions[] = 'Manage Tags';
                            if ($rank['can_manage_ranks']) $permissions[] = 'Manage Ranks';
                            if ($rank['can_manage_views']) $permissions[] = 'Manage Views';
                            if ($rank['can_manage_comments']) $permissions[] = 'Manage Comments';
                            if ($rank['can_manage_reports']) $permissions[] = 'Manage Reports';
                            if ($rank['can_manage_system_settings']) $permissions[] = 'Manage System Settings';

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
            <a href="add_rank.php" class="add-new">Add New Rank</a>
        </div>
    </main>
</body>
</html>