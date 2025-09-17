<?php
require 'conexao.php';

if (isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tarefas SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}
