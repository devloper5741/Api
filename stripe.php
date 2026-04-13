<?php
header('Content-Type: application/json');

// Simula delay de API real
usleep(rand(500000, 1500000)); // 0.5 a 1.5 segundos

// Recebe os dados do cartão
$card = isset($_POST['card']) ? trim($_POST['card']) : '';
$stripeKey = isset($_POST['stripe_key']) ? $_POST['stripe_key'] : '';

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

// Validação básica de Luhn Algorithm
function validateLuhn($number) {
    $number = preg_replace('/\D/', '', $number);
    $sum = 0;
    $length = strlen($number);
    
    for ($i = $length - 1; $i >= 0; $i--) {
        $digit = (int)$number[$i];
        if (($length - $i) % 2 == 0) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
    }
    
    return ($sum % 10) == 0;
}

// Simula diferentes respostas baseadas no cartão
$isValid = validateLuhn($number);
$lastDigit = (int)substr($number, -1);

// Lógica de demonstração:
// - Cartões com último dígito 0,2,4,6,8 = DEAD (50%)
// - Cartões com último dígito 1,3,5 = LIVE (30%)
// - Cartões com último dígito 7,9 = CHARGED (20%)

$response = [];

if (!$isValid) {
    // Cartão inválido (falha no Luhn)
    $response = [
        'status' => 'dead',
        'message' => 'Card Invalid - Luhn Check Failed',
        'card' => $card,
        'gateway' => 'Stripe SK Based',
        'details' => [
            'error_code' => 'invalid_number',
            'error_message' => 'Your card number is invalid.'
        ]
    ];
} elseif ($lastDigit % 2 == 0) {
    // DEAD - Cartão recusado
    $errors = [
        'insufficient_funds' => 'Your card has insufficient funds.',
        'card_declined' => 'Your card was declined.',
        'expired_card' => 'Your card has expired.',
        'incorrect_cvc' => 'Your card\'s security code is incorrect.',
        'processing_error' => 'An error occurred while processing your card.'
    ];
    
    $errorKey = array_rand($errors);
    
    $response = [
        'status' => 'dead',
        'message' => 'Card Declined - ' . ucfirst(str_replace('_', ' ', $errorKey)),
        'card' => $card,
        'gateway' => 'Stripe SK Based',
        'details' => [
            'error_code' => $errorKey,
            'error_message' => $errors[$errorKey]
        ]
    ];
} elseif (in_array($lastDigit, [1, 3, 5])) {
    // LIVE - Cartão válido mas não cobrado
    $response = [
        'status' => 'live',
        'message' => 'Card Valid - CVV Match',
        'card' => $card,
        'gateway' => 'Stripe SK Based',
        'details' => [
            'brand' => getBrand($number),
            'country' => 'US',
            'funding' => 'credit',
            'cvc_check' => 'pass',
            'message' => 'Card is valid and ready for transactions'
        ]
    ];
} else {
    // CHARGED - Cartão aprovado com cobrança de $1
    $response = [
        'status' => 'charged',
        'message' => 'Card Charged Successfully - $1.00 USD',
        'card' => $card,
        'gateway' => 'Stripe SK Based',
        'details' => [
            'charge_id' => 'ch_' . generateRandomString(24),
            'amount' => 1.00,
            'currency' => 'usd',
            'brand' => getBrand($number),
            'country' => 'US',
            'funding' => 'credit',
            'cvc_check' => 'pass',
            'receipt_url' => 'https://stripe.com/receipt/' . generateRandomString(16)
        ]
    ];
}

echo json_encode($response);

// Funções auxiliares
function getBrand($number) {
    $firstDigit = substr($number, 0, 1);
    $firstTwo = substr($number, 0, 2);
    
    if ($firstDigit == '4') return 'Visa';
    if (in_array($firstTwo, ['51', '52', '53', '54', '55'])) return 'Mastercard';
    if (in_array($firstTwo, ['34', '37'])) return 'American Express';
    if ($firstTwo == '60') return 'Discover';
    
    return 'Unknown';
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $string;
}
