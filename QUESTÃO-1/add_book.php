<?php
// Inclui o banco
require_once 'database.php';  // usei require_once pra não dar loop se chamar duas vezes

// Checa se veio POST - vi isso no PHP manual
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeTitulo = trim($_POST["titulo"] ?? "");  // nomei assim pra lembrar que é o título
    $nomeAutor = trim($_POST["autor"] ?? "");
    $numeroAno = intval($_POST["ano"] ?? 0);  // intval pra virar número
    
    // Validação básica - se algum vazio ou ano fora do normal, ignora
    if (strlen($nomeTitulo) > 0 && strlen($nomeAutor) > 0 && $numeroAno >= 1800 && $numeroAno <= (date("Y") + 1)) {
        // Prepared statement pra segurança - aprendi que evita SQL injection
        $inserirLivro = $minhaConexao->prepare("INSERT INTO livros (titulo, autor, ano) VALUES (:tit, :aut, :an)");
        $inserirLivro->execute([
            ':tit' => $nomeTitulo,
            ':aut' => $nomeAutor,
            ':an' => $numeroAno
        ]);
        // Não echo nada aqui, só redireciona
    } else {
        // Se inválido, redireciona mesmo assim - poderia logar erro mas é overkill pra atividade
    }
}

// Volta pro index - header sempre no final
header("Location: index.php");
exit();  // exit pra garantir que para
?>