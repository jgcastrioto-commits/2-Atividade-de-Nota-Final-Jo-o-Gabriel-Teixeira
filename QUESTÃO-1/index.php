<?php
require 'database.php';  // carrega a conexão

// Busca os livros - order by id desc pra mostrar os novos primeiro
$buscarLivros = $minhaConexao->query("SELECT * FROM livros ORDER BY id DESC");
$listaLivros = $buscarLivros->fetchAll(PDO::FETCH_ASSOC);  // fetchAll pra array

// Se der erro aqui, o try/catch do database pega
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Livraria Simples - Minha versão pra NF</title>
    <style>
        /* CSS inline como pedido - fiz manual, sem framework */
        body {
            font-family: Arial, sans-serif; /* mudei pra Arial, mais comum */
            background-color: #f9f9f9; /* cinza claro, neutro */
            margin: 0;
            padding: 20px;
        }
        .container-principal { /* nomeiei assim pra organizar */
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* sombra suave */
        }
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 2px solid #007bff; /* azulzinho */
            padding-bottom: 10px;
        }
        form {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"] {
            width: 30%;
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background: #28a745; /* verde bootstrap-like */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        .botao-excluir {
            color: #dc3545; /* vermelho */
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            background: #f8d7da;
            border-radius: 3px;
        }
        .botao-excluir:hover {
            background: #f5c6cb;
            text-decoration: underline;
        }
        /* Responsivo tosco - só pra mobile não quebrar */
        @media (max-width: 600px) {
            input[type="text"], input[type="number"] { width: 100%; }
        }
    </style>
    <script>
        // JS inline pra confirmar delete - adicionei emoji pra criatividade
        function confirmaDelecao(idLivro, nomeLivro) {
            return confirm(`🚫 Tem certeza que quer deletar "${nomeLivro}" (ID: ${idLivro})? Não dá pra desfazer!`);
        }
        
        // Validação extra no form - checa ano antes de submit
        function validaAno() {
            const anoInput = document.querySelector('input[name="ano"]');
            const anoValor = parseInt(anoInput.value);
            if (anoValor < 1800 || anoValor > new Date().getFullYear()) {
                alert('Ano tem que ser entre 1800 e agora, pô!');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container-principal">
        <h1>📚 Minha Livraria - Cadastro Básico</h1>
        
        <!-- Form pra adicionar -->
        <form action="add_book.php" method="POST" onsubmit="return validaAno()">
            <label>Título: <input type="text" name="titulo" required placeholder="Ex: O Pequeno Príncipe"></label><br>
            <label>Autor: <input type="text" name="autor" required placeholder="Ex: Antoine de Saint-Exupéry"></label><br>
            <label>Ano: <input type="number" name="ano" required min="1800" max="<?= date('Y') ?>" placeholder="Ex: 1943"></label><br>
            <button type="submit">➕ Adicionar Livro</button>
        </form>
        
        <h2>Livros na Estante (<?= count($listaLivros) ?> no total)</h2>
        <?php if (empty($listaLivros)): ?>
            <p>A estante tá vazia... Adiciona um aí!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Ano</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaLivros as $umLivro): ?>
                        <tr>
                            <td><?= htmlspecialchars($umLivro['id']) ?></td>
                            <td><?= htmlspecialchars($umLivro['titulo']) ?></td>
                            <td><?= htmlspecialchars($umLivro['autor']) ?></td>
                            <td><?= $umLivro['ano'] ?></td>
                            <td>
                                <a href="delete_book.php?id=<?= $umLivro['id'] ?>" 
                                   class="botao-excluir" 
                                   onclick="return confirmaDelecao(<?= $umLivro['id'] ?>, '<?= addslashes($umLivro['titulo']) ?>')">
                                   🗑️ Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>