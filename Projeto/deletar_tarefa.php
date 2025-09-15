<?php
require 'conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID inválido.'); window.location.href='index.php';</script>";
    exit;
}

$stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Tarefa excluída com sucesso!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Erro ao excluir tarefa.'); window.location.href='index.php';</script>";
}

$stmt->close();
$conn->close();
?>
