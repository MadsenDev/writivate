<!DOCTYPE html>
<html>
  <head>
    <title>Wiki - Manage Guides</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
  </head>
  <body>
    <?php include 'admin_sidebar.php'; ?>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
      //session_start();
      include '../config.php';

      // Fetch the user's rank number from the database
      $user_rank_number = 0;
      if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT rank_number FROM users INNER JOIN ranks ON users.rank_id = ranks.id WHERE users.id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $user_rank_number = $row['rank_number'];
        }
      }

      if ($user_rank_number < 3) {
        die("You don't have permission to access this page.");
      }

      if (isset($_POST['delete_guide'])) {
        // Delete guide
        $guide_id = $_POST['guide_id'];
    
        // Delete update logs for the guide
        $stmt = $conn->prepare("DELETE FROM guide_updates WHERE guide_id = ?");
        $stmt->bind_param("i", $guide_id);
        $stmt->execute();

        // Delete related views for the guide
        $stmt = $conn->prepare("DELETE FROM guide_views WHERE guide_id = ?");
        $stmt->bind_param("i", $guide_id);
        $stmt->execute();

    
        // Delete the guide
        $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
        $stmt->bind_param("i", $guide_id);
        $stmt->execute();
    
        header('Location: manage_guides.php');
    }    

      // Get all guides
      $stmt = $conn->prepare("SELECT guides.*, categories.name as category_name, users.username as author_username FROM guides LEFT JOIN categories ON guides.category_id = categories.id LEFT JOIN users ON guides.creator_id = users.id ORDER BY id DESC");
      $stmt->execute();
      $result = $stmt->get_result();
    ?>

    <main>
      <div class="content">
        <h1>Manage Guides <a href="add_guide.php">Add Guide</a></h1>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Author</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
              mysqli_data_seek($result, 0); // Reset the $result pointer
              while ($row = mysqli_fetch_assoc($result)) {
                $guide_id = $row['id'];
                $guide_title = $row['title'];
                $category_name = $row['category_name'];
                $author_username = $row['author_username'];

                // output guide data
                echo "<tr>";
                echo "<td>$guide_id</td>";
                echo "<td><a href=\"/guide.php?id=$guide_id\">$guide_title</a></td>";
                //echo "<td>$guide_title</td>";
                echo "<td>$category_name</td>";
                echo "<td>$author_username</td>";
                echo "<td><a href=\"edit_guide.php?id=$guide_id\">Edit</a> | <form method=\"POST\"><input type=\"hidden\" name=\"guide_id\" value=\"$guide_id\"><button type=\"submit\" name=\"delete_guide\" onclick=\"return confirm('Are you sure you want to delete this guide?')\">Delete</button></form></td>";
                echo "</tr>";
              }
            } else {
              echo "No guides found.";
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
    </body>
    </html>