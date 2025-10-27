<?php
// gerar_senha.php

// ▼▼▼ COLOQUE A SENHA QUE VOCÊ QUER USAR AQUI ▼▼▼
$senha_que_voce_quer = 'roniq079';
// ▲▲▲ COLOQUE A SENHA QUE VOCÊ QUER USAR AQUI ▲▲▲

// Gera o hash da senha usando o algoritmo padrão e seguro do PHP
$hash = password_hash($senha_que_voce_quer, PASSWORD_DEFAULT);

echo "<h1>Hash da Senha Gerado</h1>";
echo "<p><strong>Senha em texto puro:</strong> " . htmlspecialchars($senha_que_voce_quer) . "</p>";
echo "<p><strong>Hash para copiar no banco de dados:</strong></p>";
echo "<textarea rows='3' cols='80' readonly>" . $hash . "</textarea>";

// Dica de segurança: delete este arquivo do servidor após usar.