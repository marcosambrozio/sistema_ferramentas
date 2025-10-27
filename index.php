<?php
// index.php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

// Exemplo de como buscar alguns dados para o dashboard
// Ferramentas com pouca quantidade
$stmt_low_stock = $pdo->prepare("SELECT * FROM ferram_ferramentas WHERE quantidade_disponivel <= 2 AND quantidade_disponivel > 0 ORDER BY quantidade_disponivel ASC LIMIT 5"); // <-- Alterado
$stmt_low_stock->execute();
$low_stock_tools = $stmt_low_stock->fetchAll();

// Últimos empréstimos
$stmt_last_loans = $pdo->prepare("
    SELECT e.id, u.nome as usuario_nome, f.descricao as ferramenta_descricao, ei.quantidade, e.data_saida
    FROM ferram_emprestimos e
    JOIN ferram_usuarios u ON e.usuario_retirada_id = u.id
    JOIN ferram_emprestimo_itens ei ON e.id = ei.emprestimo_id
    JOIN ferram_ferramentas f ON ei.ferramenta_id = f.id
    WHERE e.status = 'Emprestado'
    ORDER BY e.data_saida DESC
    LIMIT 5
"); // <-- Alterado
$stmt_last_loans->execute();
$last_loans = $stmt_last_loans->fetchAll();
?>

<h2>Dashboard</h2>

<p>Bem-vindo ao sistema de gerenciamento de ferramentas,
    <strong><?= htmlspecialchars($_SESSION['usuario_nome']); ?></strong>!
</p>

<hr>

<h3>Ferramentas com Estoque Baixo</h3>
<?php if (count($low_stock_tools) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Disponível</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($low_stock_tools as $tool): ?>
                <tr>
                    <td><?= htmlspecialchars($tool['codigo_ferramenta']) ?></td>
                    <td><?= htmlspecialchars($tool['descricao']) ?></td>
                    <td><?= $tool['quantidade_disponivel'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhuma ferramenta com estoque baixo no momento.</p>
<?php endif; ?>

<br>

<h3>Últimas Ferramentas Emprestadas</h3>
<?php if (count($last_loans) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Ferramenta</th>
                <th>Data da Retirada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($last_loans as $loan): ?>
                <tr>
                    <td><?= htmlspecialchars($loan['usuario_nome']) ?></td>
                    <td><?= htmlspecialchars($loan['ferramenta_descricao']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($loan['data_saida'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum empréstimo ativo no momento.</p>
<?php endif; ?>


<?php
require_once __DIR__ . '/includes/footer.php';
?>