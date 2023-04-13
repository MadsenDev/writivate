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
?>