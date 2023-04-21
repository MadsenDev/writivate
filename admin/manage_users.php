<!DOCTYPE html>
<html>
<head>
	<title>Wiki - Manage Users</title>
	<link rel="icon" type="image/png" href="/public/images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
	<?php include 'admin_sidebar.php'; ?>
	<main>
		<div class="content">
			<h1>Manage Users <a href="add_user.php">Add New</a></h1>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>Email</th>
						<th>Rank</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						session_start();
						include '../config.php';

						// Fetch all users and their ranks
						$stmt = $conn->prepare("SELECT users.*, ranks.title as rank_title FROM users INNER JOIN ranks ON users.rank_id = ranks.id");
						$stmt->execute();
						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$user_id = $row['id'];
								$username = $row['username'];
								$email = $row['email'];
								$rank_title = $row['rank_title'];

								// Output user data
								echo "<tr>";
								echo "<td>$user_id</td>";
								echo "<td>$username</td>";
								echo "<td>$email</td>";
								echo "<td>$rank_title</td>";
								echo "<td><a href=\"edit_single_user.php?id=$user_id\">Edit</a> | <a href=\"delete_user.php?id=$user_id\" onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
								echo "</tr>";
							}
						} else {
							echo "No users found.";
						}
					?>
				</tbody>
			</table>
		</div>
	</main>
</body>
</html>