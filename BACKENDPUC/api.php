<?php
declare(strict_types=1);

require_once __DIR__ . '/Product.php';

header('Content-Type: application/json');

$code = $_GET['code'] ?? null;
$moneyRaw = $_GET['money'] ?? null;

if (!$code || !$moneyRaw) {
    echo json_encode(['error' => 'Não foi passado os parâmetros necessários']);
    exit;
}

$money = filter_var($moneyRaw, FILTER_VALIDATE_INT);

if ($money === false) {
    echo json_encode(['error' => 'Dinheiro deveria ser um valor inteiro']);
    exit;
}

$products = Product::loadAllFromJson(__DIR__ . '/products.json');

if (!isset($products[$code])) {
    echo json_encode(['error' => 'Produto não encontrado']);
    exit;
}

$product = $products[$code];
$price = $product->getPrice();

if ($money < $price) {
    echo json_encode([
        'error' => 'Não há moedas suficientes',
        'required' => $price,
        'provided' => $money,
    ]);
    exit;
}

$change = $money - $price;

echo json_encode([
    'product' => $product->toArray(),
    'paid' => $money,
    'change' => $change,
]);
