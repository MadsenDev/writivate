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
?>