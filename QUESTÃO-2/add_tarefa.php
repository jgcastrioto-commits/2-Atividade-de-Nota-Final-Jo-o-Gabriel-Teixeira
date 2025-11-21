<?php
require 'database.php';  // inclui conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $textoTarefa = trim($_POST["descricao"] ?? "");
    $dataVenc = $_POST["vencimento"] ?? null;  // pode ser vazio
    
    if (!empty($textoTarefa)) {  // só checa descricao, data opcional
        // Inserção com prepare - ? em vez de : pra variar
        $adicionar = $conexaoTarefas->prepare("INSERT INTO tarefas (descricao, vencimento) VALUES (?, ?)");
        $adicionar->execute([$textoTarefa, $dataVenc]);
    }
    // Se vazio, ignora silenciosamente
}

header("Location: index.php");  // sempre volta
exit();
?>