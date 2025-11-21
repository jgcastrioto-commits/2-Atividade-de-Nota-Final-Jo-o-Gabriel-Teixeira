<?php
require 'database.php';

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $idDeletar = (int) $_POST['id'];
    
    // Delete direto
    $remover = $conexaoTarefas->prepare("DELETE FROM tarefas WHERE id = ?");
    $remover->execute([$idDeletar]);
    // Não checo rows affected, assume que deu certo
}

header("Location: index.php");
exit;
?>