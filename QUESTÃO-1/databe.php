<?php
// Arquivo de conexão pro banco de livros - fiz isso depois de ver um tutorial no YouTube
try {
    // Cria a conexão com SQLite, nomeei de $minhaConexao pra ficar fácil de lembrar
    $minhaConexao = new PDO('sqlite:meus_livros.db');
    $minhaConexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Aqui cria a tabela se não existir... já rodei isso várias vezes testando
    $comandoCriarTabela = "
        CREATE TABLE IF NOT EXISTS livros (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titulo TEXT NOT NULL,
            autor TEXT NOT NULL,
            ano INTEGER NOT NULL
        )
    ";
    $minhaConexao->exec($comandoCriarTabela);
    // echo "Tabela ok!"; // tirei o echo depois pra não poluir
    
} catch (PDOException $problema) {
    // Se der erro, mostra e para - simples assim
    die("Pô, erro na conexão: " . $problema->getMessage());
}
?>