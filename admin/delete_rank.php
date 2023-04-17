<?php
include '../config.php';
include '../functions.php';

$rank_id = $_GET['id'] ?? null;

if (!$rank_id) {
    die("Invalid rank ID.");
}

$stmt = $conn->prepare("DELETE FROM ranks WHERE id = ?");
$stmt->bind_param("i", $rank_id);
$stmt->execute();

header('Location: manage_ranks.php');
?>