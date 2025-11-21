<?php
// Conexão pro banco de tarefas - copiei ideia do primeiro mas mudei nomes
try {
    $conexaoTarefas = new PDO('sqlite:minhas_tarefas.db');  // nome diferente pra não confundir
    $conexaoTarefas->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tabela com campos extras? Nah, só o básico + concluida como 0/1
    $criarTabelaTarefas = "
        CREATE TABLE IF NOT EXISTS tarefas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            descricao TEXT NOT NULL,
            vencimento TEXT,  -- guardo como TEXT pra data
            concluida INTEGER DEFAULT 0  -- 0 pendente, 1 feita
        )
    ";
    $conexaoTarefas->exec($criarTabelaTarefas);
    
} catch (PDOException $erroAqui) {
    die("Deu zica nas tarefas: " . $erroAqui->getMessage() . " - reinicia o XAMPP?");
}
?>