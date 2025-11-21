<?php
require 'database.php';

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $idTarefa = (int) $_POST['id'];
    
    // Pega status atual e inverte - 1 - concluida é gambiarra mas funciona
    $statusAtual = $conexaoTarefas->prepare("SELECT concluida FROM tarefas WHERE id = ?");
    $statusAtual->execute([$idTarefa]);
    $resultado = $statusAtual->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado) {
        $novoStatus = $resultado['concluida'] == 1 ? 0 : 1;  // inverte
        $atualizar = $conexaoTarefas->prepare("UPDATE tarefas SET concluida = ? WHERE id = ?");
        $atualizar->execute([$novoStatus, $idTarefa]);
    }
}

header("Location: index.php");
exit;
?>