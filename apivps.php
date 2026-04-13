<?php
header('Content-Type: application/json');

// Simula delay de API real (Cielo é mais rápida)
usleep(rand(300000, 800000)); // 0.3 a 0.8 segundos

// Recebe os dados do cartão
$card = isset($_POST['card']) ? trim($_POST['card']) : '';

// Parse do cartão (formato: numero|mes|ano|cvv)
$cardParts = explode('|', $card);

// Validação mais robusta
if (count($cardParts) !== 4) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Formato de cartão inválido. Use: numero|mes|ano|cvv',
        'received' => $card,
        'parts_count' => count($cardParts)
    ]);
    exit;
}

// Remove espaços em branco
$cardParts = array_map('trim', $cardParts);

list($number, $month, $year, $cvv) = $cardParts;

// Validação de BIN (primeiros 6 dígitos)
$bin = substr($number, 0, 6);

// Simula diferentes respostas baseadas no BIN
$binLastDigit = (int)substr($bin, -1);

$response = [];

// Lógica de demonstração para Cielo (AllBins):
// - BIN terminando em 0,1,2,3 = DEAD (40%)
// - BIN terminando em 4,5,6,7 = LIVE (40%)
// - BIN terminando em 8,9 = CHARGED (20%)

if ($binLastDigit <= 3) {
    // DEAD - Cartão recusado pela Cielo
    $errors = [
        ['code' => '05', 'message' => 'Não Autorizada - Contate o emissor'],
        ['code' => '51', 'message' => 'Saldo Insuficiente'],
        ['code' => '54', 'message' => 'Cartão Vencido'],
        ['code' => '57', 'message' => 'Transação não permitida'],
        ['code' => '78', 'message' => 'Cartão Bloqueado'],
        ['code' => '91', 'message' => 'Emissor fora do ar']
    ];
    
    $error = $errors[array_rand($errors)];
    
    $response = [
        'status' => 'dead',
        'message' => 'Transação Negada - Código ' . $error['code'],
        'card' => $card,
        'gateway' => 'Cielo (AllBins)',
        'details' => [
            'return_code' => $error['code'],
            'return_message' => $error['message'],
            'bin' => $bin,
            'brand' => getBrandCielo($number),
            'processor' => 'Cielo'
        ]
    ];
} elseif ($binLastDigit <= 7) {
    // LIVE - Cartão válido
    $response = [
        'status' => 'live',
        'message' => 'Cartão Válido - BIN Aprovado',
        'card' => $card,
        'gateway' => 'Cielo (AllBins)',
        'details' => [
            'return_code' => '00',
            'return_message' => 'Transação autorizada',
            'bin' => $bin,
            'brand' => getBrandCielo($number),
            'card_type' => getCardType($bin),
            'issuer_country' => getCountryFromBin($bin),
            'processor' => 'Cielo'
        ]
    ];
} else {
    // CHARGED - Transação aprovada
    $amount = rand(100, 500) / 100; // R$ 1.00 a R$ 5.00
    
    $response = [
        'status' => 'charged',
        'message' => 'Transação Aprovada - R$ ' . number_format($amount, 2, ',', '.'),
        'card' => $card,
        'gateway' => 'Cielo (AllBins)',
        'details' => [
            'return_code' => '00',
            'return_message' => 'Transação autorizada',
            'authorization_code' => generateAuthCode(),
            'tid' => generateTid(),
            'nsu' => generateNsu(),
            'amount' => $amount,
            'currency' => 'BRL',
            'bin' => $bin,
            'brand' => getBrandCielo($number),
            'card_type' => getCardType($bin),
            'issuer_country' => getCountryFromBin($bin),
            'processor' => 'Cielo',
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ];
}

echo json_encode($response);

// Funções auxiliares
function getBrandCielo($number) {
    $firstDigit = substr($number, 0, 1);
    $firstTwo = substr($number, 0, 2);
    $firstFour = substr($number, 0, 4);
    
    if ($firstDigit == '4') return 'Visa';
    if (in_array($firstTwo, ['51', '52', '53', '54', '55'])) return 'Mastercard';
    if (in_array($firstTwo, ['34', '37'])) return 'Amex';
    if ($firstFour == '6011' || $firstTwo == '65') return 'Discover';
    if (in_array($firstTwo, ['36', '38'])) return 'Diners';
    if ($firstFour == '6062') return 'Hipercard';
    if ($firstDigit == '5' && in_array($firstTwo, ['50'])) return 'Elo';
    
    return 'Unknown';
}

function getCardType($bin) {
    $types = ['credit', 'debit', 'prepaid'];
    return $types[array_rand($types)];
}

function getCountryFromBin($bin) {
    $countries = ['BR', 'US', 'AR', 'CL', 'MX', 'CO'];
    return $countries[array_rand($countries)];
}

function generateAuthCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function generateTid() {
    return date('YmdHis') . rand(1000, 9999);
}

function generateNsu() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}
