<?php
require 'conexao.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

if (!$nome || !$email) {
    echo "<script>alert('Preencha todos os campos.'); window.history.back();</script>";
    exit;
}

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
$stmt->bind_param("ss", $nome, $email);

if ($stmt->execute()) {
    echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar usuário.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
