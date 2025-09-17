<?php
require 'conexao.php';

$titulo = $_POST['titulo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$setor = $_POST['setor'] ?? '';
$usuario_id = $_POST['usuario_id'] ?? '';
$prioridade = $_POST['prioridade'] ?? '';

if (!$titulo || !$descricao || !$setor || !$usuario_id || !$prioridade) {
    echo "<script>alert('Preencha todos os campos.'); window.history.back();</script>";
    exit;
}

// Verifica se usuário existe
$stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmtCheck->bind_param("i", $usuario_id);
$stmtCheck->execute();
$stmtCheck->store_result();
if ($stmtCheck->num_rows === 0) {
    echo "<script>alert('Usuário inválido.'); window.history.back();</script>";
    exit;
}
$stmtCheck->close();

// Insere tarefa
$stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, setor, usuario_id, prioridade) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssis", $titulo, $descricao, $setor, $usuario_id, $prioridade);

if ($stmt->execute()) {
    echo "<script>alert('Tarefa cadastrada com sucesso!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar tarefa.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
