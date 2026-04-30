<?php
function buildFolderTree($folders, $parentId = null) {
    $html = "<ul>";
    foreach ($folders as $folder) {
        if ($folder['parent_id'] == $parentId) {
            $html .= "<li>" . htmlspecialchars($folder['name']);
            // Recursive call to find subfolders
            $html .= buildFolderTree($folders, $folder['id']);
            $html .= "</li>";
        }
    }
    $html .= "</ul>";
    return $html;
}