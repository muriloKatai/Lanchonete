<?php 
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    HEADER ('Location: index.php');
    exit;
}

// lê os campos do formulário e aceita somente os números

$codigo = filter_input(INPUT_POST, 'id_lanche', FILTER_VALIDATE_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

// Indentifica um valor invalido
if (!$codigo || $codigo < 1){
        exit(' Código do produto inválido.
        <a href="index.php">Voltar</a>');
}

//Indentifica um valor invalido
if (!$codigo || $codigo < 1 || $quantidade > 100){
        exit(' Código do produto inválido.
        <a href="index.php">Voltar</a>');
}

// Busca o nome e preço de um produto ativo no banco
$sql = 'SELECT nome, preco_cliente FROM produto WHERE id_lanche = ? AND ativo = 1';
$consulta = $conexao->prepare($sql);
$consulta->execute([$codigo]);

$produto = $consulta->fetch(PDO::FETCH_ASSOC);

// Verifica se a busca encontrou produto ativo,
// Caso contrário interronpe busca
if (!$produto){
    exit('Produto não encontrado ou inativo. <a href="index.php">Voltar</a>');
}


// O preço pode ser zero, mas nao vazio null ou negativo
if ($produto['preco_cliente'] === null || 
    $produto['preco_cliente'] < 0) {
        exit('Produto sem preço definido.
        <a href="index.php">Voltar</a>');
    }

// calculo do preço unitário do lanche * quantidade
$total = $produto['preco_cliente'] * $quantidade;
?>

<!DOCTYPE html>
<html lang="pt-br">
<html>
<head>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido</title>

    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main class="container resumo">
    
    <h1>Resumo do pedido</h1>
    <p>Produto: <?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?> </p>
    <p>Quantidade: <?php echo number_format($produto['preco_cliente'],2,',','.')?> </p>
    
    <h2>Total: R$ <?php echo number_format($total, 2,',', '.') ?></h2>
    <p><a href="index.php">Voltar ao Cardápio</a></p>
</main>

</body>
</html>