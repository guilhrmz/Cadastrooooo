<?php
require 'conexao.php';

// Buscar usuários para select do cadastro tarefa
$usuarios = [];
$result = $conn->query("SELECT id, nome FROM usuarios ORDER BY nome");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Buscar tarefas para gerenciamento
$tarefas = [];
$sql = "SELECT t.id, t.titulo, t.descricao, t.setor, t.prioridade, u.nome as usuario_nome 
        FROM tarefas t 
        JOIN usuarios u ON t.usuario_id = u.id
        ORDER BY t.id DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tarefas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gerenciamento de Tarefas</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0; padding: 0;
  }
  .navbar {
    padding: 0.8rem;
  }
  .navbar-nav-left {
    margin-right: auto;
  }
  .navbar-nav-right {
    margin-left: auto;
  }
  .content-section {
    margin-top: 20px;
    max-width: 100%;
    padding-left: 15px;
    padding-right: 15px;
  }
</style>
</head>
<body>

<!-- Navbar azul -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Minha Aplicação</a>

    <div class="navbar-nav navbar-nav-left d-flex gap-2">
      <button class="btn btn-primary nav-link" onclick="mostrarSecao('cadastroUsuario')">Cadastro de Usuário</button>
      <button class="btn btn-primary nav-link" onclick="mostrarSecao('cadastroTarefa')">Cadastro de Tarefas</button>
    </div>

    <div class="navbar-nav navbar-nav-right d-flex gap-2">
      <button class="btn btn-primary nav-link" onclick="mostrarSecao('gerenciarTarefa')">Gerenciar Tarefas</button>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Cadastro de Usuário -->
  <div id="cadastroUsuario" class="content-section" style="display:none;">
    <h2>Cadastro de Usuário</h2>
    <form action="salvar_usuario.php" method="POST" id="formUsuario">
      <div class="mb-3">
        <label for="nomeUsuario" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nomeUsuario" name="nome" placeholder="Digite o nome" required />
      </div>
      <div class="mb-3">
        <label for="emailUsuario" class="form-label">Email</label>
        <input type="email" class="form-control" id="emailUsuario" name="email" placeholder="Digite o email" required />
      </div>
      <button type="submit" class="btn btn-success">Cadastrar Usuário</button>
    </form>
  </div>

  <!-- Cadastro de Tarefas -->
  <div id="cadastroTarefa" class="content-section" style="display:block;">
    <h2>Cadastro de Tarefa</h2>
    <form action="salvar_tarefa.php" method="POST" id="formTarefa">
      <div class="mb-3">
        <label for="tituloTarefa" class="form-label">Título</label>
        <input type="text" class="form-control" id="tituloTarefa" name="titulo" placeholder="Título da tarefa" required />
      </div>
      <div class="mb-3">
        <label for="descricaoTarefa" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricaoTarefa" name="descricao" rows="3" placeholder="Descrição da tarefa" required></textarea>
      </div>

      <div class="mb-3">
        <label for="setor" class="form-label">Setor</label>
        <select class="form-control" id="setor" name="setor" required>
          <option value="TI">TI</option>
          <option value="Financeiro">Financeiro</option>
          <option value="RH">RH</option>
          <option value="Marketing">Marketing</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="usuario" class="form-label">Usuário</label>
        <select class="form-control" id="usuario" name="usuario_id" required>
          <option value="" disabled selected>Selecione o usuário</option>
          <?php foreach ($usuarios as $user): ?>
            <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="prioridade" class="form-label">Prioridade</label>
        <select class="form-control" id="prioridade" name="prioridade" required>
          <option value="baixa">Baixa</option>
          <option value="media">Média</option>
          <option value="alta">Alta</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>
  </div>

  <!-- Gerenciar Tarefas -->
  <div id="gerenciarTarefa" class="content-section" style="display:none;">
    <h2>Gerenciar Tarefas</h2>

    <?php if (count($tarefas) === 0): ?>
      <p>Nenhuma tarefa cadastrada.</p>
    <?php else: ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Setor</th>
            <th>Usuário</th>
            <th>Prioridade</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tarefas as $t): ?>
            <tr>
              <td><?= htmlspecialchars($t['id']) ?></td>
              <td><?= htmlspecialchars($t['titulo']) ?></td>
              <td><?= nl2br(htmlspecialchars($t['descricao'])) ?></td>
              <td><?= htmlspecialchars($t['setor']) ?></td>
              <td><?= htmlspecialchars($t['usuario_nome']) ?></td>
              <td><?= ucfirst(htmlspecialchars($t['prioridade'])) ?></td>
              <td>
                <a href="deletar_tarefa.php?id=<?= $t['id'] ?>" onclick="return confirm('Confirma exclusão da tarefa?');" class="btn btn-danger btn-sm">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function mostrarSecao(id) {
    const secoes = document.querySelectorAll('.content-section');
    secoes.forEach(secao => (secao.style.display = 'none'));
    document.getElementById(id).style.display = 'block';
  }
</script>

</body>
</html>
