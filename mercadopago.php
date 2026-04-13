<?php
header('Content-Type: application/json');

// Simula delay de API real do Mercado Pago
usleep(rand(400000, 1200000)); // 0.4 a 1.2 segundos

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

// Simula diferentes respostas baseadas nos últimos 2 dígitos
$lastTwoDigits = (int)substr($number, -2);

$response = [];

// Lógica de demonstração para Mercado Pago:
// - Últimos 2 dígitos 00-39 = DEAD (40%)
// - Últimos 2 dígitos 40-79 = LIVE (40%)
// - Últimos 2 dígitos 80-99 = CHARGED (20%)

if ($lastTwoDigits <= 39) {
    // DEAD - Pagamento rejeitado
    $rejectionReasons = [
        ['code' => 'cc_rejected_insufficient_amount', 'message' => 'Saldo insuficiente'],
        ['code' => 'cc_rejected_bad_filled_security_code', 'message' => 'Código de segurança inválido'],
        ['code' => 'cc_rejected_bad_filled_date', 'message' => 'Data de validade inválida'],
        ['code' => 'cc_rejected_bad_filled_card_number', 'message' => 'Número do cartão inválido'],
        ['code' => 'cc_rejected_blacklist', 'message' => 'Cartão na lista negra'],
        ['code' => 'cc_rejected_call_for_authorize', 'message' => 'Contate o banco emissor'],
        ['code' => 'cc_rejected_card_disabled', 'message' => 'Cartão desabilitado'],
        ['code' => 'cc_rejected_duplicated_payment', 'message' => 'Pagamento duplicado'],
        ['code' => 'cc_rejected_high_risk', 'message' => 'Transação de alto risco']
    ];
    
    $rejection = $rejectionReasons[array_rand($rejectionReasons)];
    
    $response = [
        'status' => 'dead',
        'message' => 'Pagamento Rejeitado - ' . $rejection['message'],
        'card' => $card,
        'gateway' => 'Mercado Pago',
        'details' => [
            'status' => 'rejected',
            'status_detail' => $rejection['code'],
            'status_message' => $rejection['message'],
            'payment_id' => rand(1000000000, 9999999999),
            'payment_method_id' => getPaymentMethodMP($number),
            'issuer_id' => rand(100, 999),
            'card_brand' => getBrandMP($number)
        ]
    ];
} elseif ($lastTwoDigits <= 79) {
    // LIVE - Cartão válido (aprovado sem cobrança real)
    $response = [
        'status' => 'live',
        'message' => 'Cartão Válido - Aprovado para Transações',
        'card' => $card,
        'gateway' => 'Mercado Pago',
        'details' => [
            'status' => 'approved',
            'status_detail' => 'accredited',
            'status_message' => 'Cartão válido e aprovado',
            'payment_id' => rand(1000000000, 9999999999),
            'payment_method_id' => getPaymentMethodMP($number),
            'issuer_id' => rand(100, 999),
            'card_brand' => getBrandMP($number),
            'card_type' => getCardTypeMP(),
            'first_six_digits' => substr($number, 0, 6),
            'last_four_digits' => substr($number, -4)
        ]
    ];
} else {
    // CHARGED - Pagamento aprovado
    $amount = rand(100, 1000) / 100; // R$ 1.00 a R$ 10.00
    
    $response = [
        'status' => 'charged',
        'message' => 'Pagamento Aprovado - R$ ' . number_format($amount, 2, ',', '.'),
        'card' => $card,
        'gateway' => 'Mercado Pago',
        'details' => [
            'status' => 'approved',
            'status_detail' => 'accredited',
            'status_message' => 'Pagamento aprovado',
            'payment_id' => rand(1000000000, 9999999999),
            'authorization_code' => generateAuthCodeMP(),
            'transaction_amount' => $amount,
            'currency_id' => 'BRL',
            'payment_method_id' => getPaymentMethodMP($number),
            'issuer_id' => rand(100, 999),
            'card_brand' => getBrandMP($number),
            'card_type' => getCardTypeMP(),
            'first_six_digits' => substr($number, 0, 6),
            'last_four_digits' => substr($number, -4),
            'installments' => 1,
            'transaction_details' => [
                'net_received_amount' => $amount * 0.95,
                'total_paid_amount' => $amount,
                'installment_amount' => $amount
            ],
            'date_approved' => date('Y-m-d\TH:i:s.000-03:00'),
            'date_created' => date('Y-m-d\TH:i:s.000-03:00')
        ]
    ];
}

echo json_encode($response);

// Funções auxiliares
function getBrandMP($number) {
    $firstDigit = substr($number, 0, 1);
    $firstTwo = substr($number, 0, 2);
    $firstFour = substr($number, 0, 4);
    
    if ($firstDigit == '4') return 'visa';
    if (in_array($firstTwo, ['51', '52', '53', '54', '55'])) return 'master';
    if (in_array($firstTwo, ['34', '37'])) return 'amex';
    if ($firstFour == '6062') return 'hipercard';
    if (in_array($firstFour, ['5067', '5090', '6277', '6363'])) return 'elo';
    
    return 'unknown';
}

function getPaymentMethodMP($number) {
    $brand = getBrandMP($number);
    
    $methods = [
        'visa' => 'visa',
        'master' => 'master',
        'amex' => 'amex',
        'hipercard' => 'hipercard',
        'elo' => 'elo'
    ];
    
    return isset($methods[$brand]) ? $methods[$brand] : 'unknown';
}

function getCardTypeMP() {
    $types = ['credit_card', 'debit_card'];
    return $types[array_rand($types)];
}

function generateAuthCodeMP() {
    return rand(100000, 999999);
}
