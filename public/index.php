<?php
require_once __DIR__ . '/../src/UserRepository.php';
$repo = new UserRepository(realpath(__DIR__ . '/../data/users.json') ?: __DIR__ . '/../data/users.json');
$users = $repo->getAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CRUD de Usuários</title>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="theme-color" content="#317EFB" />
</head>
<body>
<div class="container">
    <header>
        <h1>Usuários</h1>
        <button id="btnNew" class="btn primary">Novo usuário</button>
    </header>

    <main>
        <div class="table-wrap">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr data-id="<?= htmlspecialchars($u['id']) ?>">
                            <td><?= htmlspecialchars($u['id']) ?></td>
                            <td><?= htmlspecialchars($u['name']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['phone']) ?></td>
                            <td>
                                <button class="btn small edit">Editar</button>
                                <button class="btn small danger delete">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="modal" class="modal" aria-hidden="true">
    <div class="modal-content">
        <h2 id="modalTitle">Novo usuário</h2>
        <form id="userForm">
            <input type="hidden" name="id" id="userId">
            <label>Nome
                <input type="text" name="name" id="name" required>
            </label>
            <label>E-mail
                <input type="email" name="email" id="email" required>
            </label>
            <label>Telefone
                <input type="text" name="phone" id="phone" required>
            </label>
            <div class="form-actions">
                <button type="button" class="btn" id="btnCancel">Cancelar</button>
                <button type="submit" class="btn primary" id="btnSave">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/app.js"></script>
</body>
</html>
