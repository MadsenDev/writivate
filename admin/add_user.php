<!DOCTYPE html>
<html>
<head>
	<title>Wiki - Add User</title>
	<link rel="icon" type="image/png" href="/public/images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
	<?php include 'admin_sidebar.php'; ?>
	<main>
		<div class="content">
			<h1>Add User</h1>
			<form method="post" action="process_add_user.php">
				<label for="username">Username:</label>
				<input type="text" name="username" required><br>

				<label for="password">Password:</label>
				<input type="password" name="password" required><br>

				<label for="email">Email:</label>
				<input type="email" name="email" required><br>

				<label for="rank_id">Rank:</label>
				<select name="rank_id">
					<?php
						include '../config.php';

						// Fetch all ranks
						$stmt = $conn->prepare("SELECT * FROM ranks");
						$stmt->execute();
						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$rank_id = $row['id'];
								$rank_title = $row['title'];

								echo "<option value=\"$rank_id\">$rank_title</option>";
							}
						} else {
							echo "No ranks found.";
						}
					?>
				</select><br><br>

				<input type="submit" value="Add User">
			</form>
		</div>
	</main>
</body>
</html>