<?php
require 'conexao.php';

// Buscar usuários
$usuarios = [];
$result = $conn->query("SELECT id, nome FROM usuarios ORDER BY nome");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Buscar tarefas por status
$tarefas = ['afazer' => [], 'fazendo' => [], 'concluido' => []];
$sql = "SELECT t.id, t.titulo, t.descricao, t.setor, t.prioridade, t.status, u.nome as usuario_nome 
        FROM tarefas t 
        JOIN usuarios u ON t.usuario_id = u.id
        ORDER BY t.id DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tarefas[$row['status']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gerenciamento de Tarefas - Kanban</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="style.css">
<style>
.kanban-board {
  display: flex;
  gap: 1rem;
}
.kanban-column {
  flex: 1;
  background: #f8f9fa;
  border-radius: 8px;
  padding: 10px;
  min-height: 400px;
}
.kanban-column h5 {
  text-align: center;
  margin-bottom: 10px;
}
.task-card {
  background: #fff;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 10px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  cursor: grab;
}
.task-card.dragging {
  opacity: 0.5;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Gerenciamento de Tarefas</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#" onclick="mostrarSecao('cadastroUsuario')">Cadastro de Usuário</a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="mostrarSecao('cadastroTarefa')">Cadastro de Tarefa</a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="mostrarSecao('gerenciarTarefa')">Gerenciar Tarefas</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">

  <!-- Cadastro Usuário -->
  <div id="cadastroUsuario" class="content-section" style="display:none;">
    <h3 class="mb-3">Cadastro de Usuário</h3>
    <form action="salvar_usuario.php" method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>
  </div>

  <!-- Cadastro Tarefa -->
  <div id="cadastroTarefa" class="content-section">
    <h3 class="mb-3">Cadastro de Tarefa</h3>
    <form action="salvar_tarefa.php" method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Setor</label>
        <select name="setor" class="form-control" required>
          <option value="TI">TI</option>
          <option value="Financeiro">Financeiro</option>
          <option value="RH">RH</option>
          <option value="Marketing">Marketing</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Usuário</label>
        <select name="usuario_id" class="form-control" required>
          <option value="">Selecione...</option>
          <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Prioridade</label>
        <select name="prioridade" class="form-control" required>
          <option value="baixa">Baixa</option>
          <option value="media">Média</option>
          <option value="alta">Alta</option>
        </select>
      </div>
      <input type="hidden" name="status" value="afazer">
      <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>
  </div>

  <!-- Kanban -->
  <div id="gerenciarTarefa" class="content-section" style="display:none;">
    <h3 class="mb-3">Quadro Kanban</h3>
    <div class="kanban-board">

      <!-- Coluna A Fazer -->
      <div class="kanban-column" data-status="afazer">
        <h5>A Fazer</h5>
        <?php foreach ($tarefas['afazer'] as $t): ?>
          <div class="task-card" draggable="true" data-id="<?= $t['id'] ?>">
            <strong><?= htmlspecialchars($t['titulo']) ?></strong><br>
            <small><?= htmlspecialchars($t['usuario_nome']) ?></small><br>
            <span class="badge bg-info"><?= ucfirst($t['prioridade']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Coluna Fazendo -->
      <div class="kanban-column" data-status="fazendo">
        <h5>Fazendo</h5>
        <?php foreach ($tarefas['fazendo'] as $t): ?>
          <div class="task-card" draggable="true" data-id="<?= $t['id'] ?>">
            <strong><?= htmlspecialchars($t['titulo']) ?></strong><br>
            <small><?= htmlspecialchars($t['usuario_nome']) ?></small><br>
            <span class="badge bg-info"><?= ucfirst($t['prioridade']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Coluna Concluído -->
      <div class="kanban-column" data-status="concluido">
        <h5>Concluído</h5>
        <?php foreach ($tarefas['concluido'] as $t): ?>
          <div class="task-card" draggable="true" data-id="<?= $t['id'] ?>">
            <strong><?= htmlspecialchars($t['titulo']) ?></strong><br>
            <small><?= htmlspecialchars($t['usuario_nome']) ?></small><br>
            <span class="badge bg-info"><?= ucfirst($t['prioridade']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>

</div>

<script>
function mostrarSecao(id) {
  document.querySelectorAll('.content-section').forEach(secao => secao.style.display = 'none');
  document.getElementById(id).style.display = 'block';
}

// Drag and drop
const cards = document.querySelectorAll(".task-card");
const columns = document.querySelectorAll(".kanban-column");

cards.forEach(card => {
  card.addEventListener("dragstart", () => {
    card.classList.add("dragging");
  });
  card.addEventListener("dragend", () => {
    card.classList.remove("dragging");
  });
});

columns.forEach(column => {
  column.addEventListener("dragover", e => {
    e.preventDefault();
    const dragging = document.querySelector(".dragging");
    if (dragging) {
      column.appendChild(dragging);
      // Atualiza no servidor
      const id = dragging.dataset.id;
      const status = column.dataset.status;
      fetch("update_status.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${id}&status=${status}`
      });
    }
  });
});
</script>

</body>
</html>
