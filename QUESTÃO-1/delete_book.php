<?php
require 'database.php';  // sem _once aqui, mas não tem problema

// Pega o ID do GET e checa se é número
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idParaDeletar = (int) $_GET['id'];  // cast pra int
    
    // Delete simples com prepare
    $deletar = $minhaConexao->prepare("DELETE FROM livros WHERE id = :id");
    $deletar->execute([':id' => $idParaDeletar]);
    
    // Poderia checar se deletou algo, mas pra simplificar não
}

// Redireciona de volta
header("Location: index.php");
exit;
?>