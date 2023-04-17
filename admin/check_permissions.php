<?php
function check_permission($user_rank_id, $permission) {
  global $conn;
  
  $stmt = $conn->prepare("SELECT * FROM ranks WHERE id = ?");
  $stmt->bind_param("i", $user_rank_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $rank = $result->fetch_assoc();
  
  return $rank[$permission] == 1;
}

function get_user_rank_id($conn, $username) {
    $user_rank_id = '';
    $stmt = $conn->prepare("SELECT rank_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_rank_id = $row['rank_id'];
    }
    return $user_rank_id;
}
?>