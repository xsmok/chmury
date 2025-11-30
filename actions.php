<?php

require_once __DIR__ . '/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $title = trim($_POST['title'] ?? '');
        if ($title !== '') {
            $stmt = $db->prepare('INSERT INTO todos (title) VALUES (?)');
            $stmt->bind_param('s', $title);
            $stmt->execute();
        }
        break;

    case 'toggle':
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare('UPDATE todos SET is_completed = NOT is_completed WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }
        break;

    case 'edit':
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        if ($id > 0 && $title !== '') {
            $stmt = $db->prepare('UPDATE todos SET title = ? WHERE id = ?');
            $stmt->bind_param('si', $title, $id);
            $stmt->execute();
        }
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare('DELETE FROM todos WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }
        break;
}

header('Location: index.php');
exit;
