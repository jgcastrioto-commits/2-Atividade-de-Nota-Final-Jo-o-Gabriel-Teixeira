<?php
require 'database.php';

// Busca pendentes: order by vencimento, mas nulls last (usei CASE pra isso)
$pendentesQuery = $conexaoTarefas->query("
    SELECT * FROM tarefas 
    WHERE concluida = 0 
    ORDER BY CASE WHEN vencimento IS NULL THEN 1 ELSE 0 END, vencimento ASC
");
$listaPendentes = $pendentesQuery->fetchAll(PDO::FETCH_ASSOC);

// Concluídas por id desc
$concluidasQuery = $conexaoTarefas->query("SELECT * FROM tarefas WHERE concluida = 1 ORDER BY id DESC");
$listaConcluidas = $concluidasQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>To-Do List Minha - Pra NF da Prof</title>
    <style>
        /* CSS variado - misturei units */
        body {
            font-family: 'Helvetica Neue', Helvetica, sans-serif; /* mudei fonte */
            background: linear-gradient(to bottom, #f0f8ff, #e6f3ff); /* gradiente pra criatividade */
            margin: 0; padding: 15px;
        }
        .wrapper {
            max-width: 700px;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        h1 { color: #495057; text-align: center; margin-bottom: 30px; }
        h2 { color: #6c757d; border-bottom: 1px solid #dee2e6; padding-bottom: 8px; margin-top: 25px; }
        form.add-form {
            background: #f8f9fa;
            padding: 18px;
            border-left: 4px solid #17a2b8; /* azul pra destacar */
            border-radius: 6px;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"] {
            width: 70%; padding: 10px; margin: 5px 0; border: 1px solid #ced4da; border-radius: 4px;
        }
        button {
            padding: 10px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; margin: 3px;
        }
        .btn-add { background: #28a745; } /* verde */
        .btn-add:hover { background: #218838; }
        .btn-toggle { background: #ffc107; color: #212529; } /* amarelo pra pendente */
        .btn-undo { background: #fd7e14; } /* laranja pra desfazer */
        .btn-delete { background: #dc3545; } /* vermelho */
        .btn-delete:hover { background: #c82333; }
        ul { list-style: none; padding: 0; }
        li {
            background: #f8f9fa;
            margin: 10px 0;
            padding: 15px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #28a745;
        }
        li.concluida {
            border-left-color: #6c757d;
            opacity: 0.7;
            text-decoration: line-through;
        }
        .info-data { font-size: 0.9em; color: #6c757d; margin-top: 5px; }
        .atrasada { color: #dc3545 !important; font-weight: bold; } /* classe pra JS */
    </style>
    <script>
        // JS pra checar se tarefa tá atrasada e colorir
        window.onload = function() {
            const itensPendentes = document.querySelectorAll('li:not(.concluida)');
            itensPendentes.forEach(function(item) {
                const dataSpan = item.querySelector('.info-data');
                if (dataSpan && dataSpan.textContent.includes('Atrasada')) {
                    item.classList.add('atrasada');
                    item.style.borderLeftColor = '#dc3545';
                }
            });
        };
        
        // Confirma delete com emoji
        function confirmaExclusao(descricao) {
            return confirm(`🗑️ Excluir "${descricao}"? Vai embora!`);
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <h1>📝 Meu Gerenciador de Tarefas (To-Do)</h1>
        
        <!-- Form adicionar -->
        <div class="add-form">
            <form action="add_tarefa.php" method="POST">
                <input type="text" name="descricao" required placeholder="Ex: Estudar PHP pra NF" maxlength="100">
                <input type="date" name="vencimento">
                <button type="submit" class="btn-add">➕ Nova Tarefa</button>
            </form>
        </div>
        
        <h2>🔄 Pendentes (<?= count($listaPendentes) ?>)</h2>
        <?php if (empty($listaPendentes)): ?>
            <p>Parabéns! Zero pendentes hoje.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($listaPendentes as $umaTarefa): 
                    $dataFormatada = $umaTarefa['vencimento'] ? date('d/m/Y', strtotime($umaTarefa['vencimento'])) : 'Sem data';
                    $isAtrasada = $umaTarefa['vencimento'] && strtotime($umaTarefa['vencimento']) < time() ? ' (Atrasada!)' : '';
                ?>
                    <li>
                        <div>
                            <strong><?= htmlspecialchars($umaTarefa['descricao']) ?></strong>
                            <div class="info-data">Vence: <?= $dataFormatada . $isAtrasada ?></div>
                        </div>
                        <div>
                            <form method="POST" action="update_tarefa.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $umaTarefa['id'] ?>">
                                <button type="submit" class="btn-toggle">✅ Concluir</button>
                            </form>
                            <form method="POST" action="delete_tarefa.php" style="display: inline;" onsubmit="return confirmaExclusao('<?= addslashes($umaTarefa['descricao']) ?>')">
                                <input type="hidden" name="id" value="<?= $umaTarefa['id'] ?>">
                                <button type="submit" class="btn-delete">🗑️ Excluir</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <h2>✅ Concluídas (<?= count($listaConcluidas) ?>)</h2>
        <?php if (empty($listaConcluidas)): ?>
            <p>Nenhuma concluída ainda... Bora marcar!</p>
        <?php else: ?>
            <ul>
                <?php foreach ($listaConcluidas as $tarefaFeita): 
                    $dataF = $tarefaFeita['vencimento'] ? date('d/m/Y', strtotime($tarefaFeita['vencimento'])) : 'Sem data';
                ?>
                    <li class="concluida">
                        <div>
                            <strong><?= htmlspecialchars($tarefaFeita['descricao']) ?></strong>
                            <div class="info-data">Venceu: <?= $dataF ?></div>
                        </div>
                        <div>
                            <form method="POST" action="update_tarefa.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $tarefaFeita['id'] ?>">
                                <button type="submit" class="btn-undo">↩️ Desmarcar</button>
                            </form>
                            <form method="POST" action="delete_tarefa.php" style="display: inline;" onsubmit="return confirmaExclusao('<?= addslashes($tarefaFeita['descricao']) ?>')">
                                <input type="hidden" name="id" value="<?= $tarefaFeita['id'] ?>">
                                <button type="submit" class="btn-delete">🗑️ Excluir</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>