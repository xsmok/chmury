<?php
require_once __DIR__ . '/db.php';

$todos = $db->query('SELECT * FROM todos ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
$editId = (int)($_GET['edit'] ?? 0);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-check2-square"></i> TODO App</h4>
                    </div>
                    <div class="card-body">
                        <form action="actions.php" method="post" class="mb-4">
                            <input type="hidden" name="action" value="add">
                            <div class="input-group">
                                <input type="text" name="title" class="form-control" placeholder="Nowe zadanie..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg"></i> Dodaj
                                </button>
                            </div>
                        </form>

                        <ul class="list-group">
                            <?php foreach ($todos as $todo): ?>
                                <li class="list-group-item d-flex align-items-center gap-2">
                                    <?php if ($editId === $todo['id']): ?>
                                        <form action="actions.php" method="post" class="d-flex flex-grow-1 gap-2">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                                            <input type="text" name="title" class="form-control form-control-sm" value="<?= htmlspecialchars($todo['title']) ?>" required>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <a href="index.php" class="btn btn-secondary btn-sm">
                                                <i class="bi bi-x"></i>
                                            </a>
                                        </form>
                                    <?php else: ?>
                                        <a href="actions.php?action=toggle&id=<?= $todo['id'] ?>" class="text-decoration-none">
                                            <?php if ($todo['is_completed']): ?>
                                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                            <?php else: ?>
                                                <i class="bi bi-circle text-secondary fs-5"></i>
                                            <?php endif; ?>
                                        </a>
                                        <span class="flex-grow-1 <?= $todo['is_completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                            <?= htmlspecialchars($todo['title']) ?>
                                        </span>
                                        <a href="index.php?edit=<?= $todo['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="actions.php?action=delete&id=<?= $todo['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Na pewno usunac?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>

                            <?php if (empty($todos)): ?>
                                <li class="list-group-item text-center text-muted">
                                    <i class="bi bi-inbox"></i> Brak zadan
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
