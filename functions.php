<?php
    function build_category_tree($categories, $parent_id = 0) {
    $tree = [];

    foreach ($categories as $category) {
        if ($category['parent_id'] == $parent_id) {
            $children = build_category_tree($categories, $category['id']);
            if ($children) {
                $category['children'] = $children;
            }
            $tree[] = $category;
        }
    }

    return $tree;
    }

    function generateCategoryOptions($categories, $parent_id = null, $indent = "")
    {
        $options = "";

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parent_id) {
                $options .= "<option value=\"{$category['id']}\">{$indent}{$category['name']}</option>";
                $options .= generateCategoryOptions($categories, $category['id'], $indent . "&emsp;");
            }
        }

        return $options;
    }

    function insertGuideView($guide_id, $user_id) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO guide_views (guide_id, user_id, view_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $guide_id, $user_id);
        $stmt->execute();
        return $conn->insert_id;
      }
      
      function updateGuideViewDuration($view_id, $duration) {
        global $conn;
        $stmt = $conn->prepare("UPDATE guide_views SET duration = ? WHERE id = ?");
        $stmt->bind_param("di", $duration, $view_id);
        $stmt->execute();
      }                    
      

  function get_full_category_path($conn, $category_id) {
    $path = "";
    while ($category_id != NULL) {
      $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
      $stmt->bind_param("i", $category_id);
      $stmt->execute();
      $category = $stmt->get_result()->fetch_assoc();
      $category_name = $category['name'];
      $category_id = $category['parent_id'];
      $path = $category_name . ($path ? " > " . $path : "");
    }
    return $path;
  }


function get_category_id_by_name($conn, $name) {
  $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row ? $row['id'] : null;
}

function get_newest_guides_by_category($conn, $category_id, $limit) {
  $category_ids = get_all_child_category_ids($conn, $category_id);
  array_push($category_ids, $category_id);
  $placeholders = implode(',', array_fill(0, count($category_ids), '?'));

  // Merge the $category_ids array with the $limit value
  $params = array_merge($category_ids, [$limit]);

  $stmt = $conn->prepare("SELECT * FROM guides WHERE category_id IN ($placeholders) ORDER BY created_at DESC LIMIT ?");
  
  // Use the $params array with the unpacking operator
  $types = str_repeat("i", count($params));
  $stmt->bind_param($types, ...$params);
  
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_all_child_category_ids($conn, $parent_id) {
  $stmt = $conn->prepare("SELECT id FROM categories WHERE parent_id = ?");
  $stmt->bind_param("i", $parent_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $child_category_ids = [];
  while ($row = $result->fetch_assoc()) {
    $child_category_ids[] = $row['id'];
    $child_category_ids = array_merge($child_category_ids, get_all_child_category_ids($conn, $row['id']));
  }
  return $child_category_ids;
}

function get_setting_value($conn, $name) {
  $stmt = $conn->prepare("SELECT value FROM settings WHERE name = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
      return $result->fetch_assoc()['value'];
  }
  return null;
}
  
?>