<?php
require_once 'config/database.php';
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];
$id      = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        'id'      => $id,
        'user_id' => $user_id
    ]);
}

header("Location: index.php");
exit;