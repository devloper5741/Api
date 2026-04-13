<?php
error_reporting(0);

$apis = [
    ['name' => '💳 STRIPE SK BASED (1$ CVV)', 'file' => 'stripe.php'],
    ['name' => '🏦 ALLBINS (CIELO)', 'file' => 'apivps.php'],
    ['name' => '� SMERCADO PAGO', 'file' => 'mercadopago.php']
];

$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PladixCentral - Checker Plus">
    <meta name="theme-color" content="#0a0a0a">
    <title>PladixCentral - Checker Plus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        ::-webkit-scrollbar-thumb {
            background: #1a1a1a;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2a2a2a;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #0f0f0f 50%, #050505 100%);
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, rgba(0, 255, 200, 0.08) 0%, rgba(0, 168, 255, 0.08) 50%, rgba(0, 255, 200, 0.08) 100%);
            border-bottom: 2px solid rgba(0, 255, 200, 0.2);
            padding: 40px 20px;
            position: relative;
            box-shadow: 0 8px 32px rgba(0, 255, 200, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .header-title {
            font-size: 42px;
            font-weight: 700;
            background: linear-gradient(90deg, #00ffc8 0%, #00a8ff 50%, #00ffc8 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 2px;
            margin-bottom: 12px;
            text-shadow: 0 0 30px rgba(0, 255, 200, 0.2);
            animation: glow 3s ease-in-out infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .header-title i {
            font-size: 38px;
            background: linear-gradient(135deg, #00ffc8 0%, #00a8ff 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 15px rgba(0, 255, 200, 0.4));
        }

        @keyframes glow {
            0%, 100% { filter: drop-shadow(0 0 10px rgba(0, 255, 200, 0.3)); }
            50% { filter: drop-shadow(0 0 20px rgba(0, 168, 255, 0.3)); }
        }

        .header-subtitle {
            font-size: 14px;
            color: #999999;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
            flex: 1;
            width: 100%;
        }

        .section {
            margin-bottom: 24px;
        }

        .section-label {
            font-size: 13px;
            font-weight: 600;
            color: #00ffc8;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-label i {
            font-size: 14px;
        }

        .input-group {
            position: relative;
        }

        textarea {
            width: 100%;
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(0, 255, 200, 0.15);
            border-radius: 12px;
            color: #cccccc;
            padding: 16px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            resize: vertical;
            min-height: 140px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        textarea:focus {
            outline: none;
            border-color: #00ffc8;
            background: rgba(20, 20, 20, 0.95);
            box-shadow: 0 0 20px rgba(0, 255, 200, 0.15);
            color: #ffffff;
        }

        textarea::placeholder {
            color: #555555;
        }

        select, input[type="text"] {
            width: 100%;
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(0, 255, 200, 0.15);
            border-radius: 12px;
            color: #ffffff;
            padding: 14px 16px;
            font-size: 14px;
            appearance: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2300ffc8' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        select:focus, input[type="text"]:focus {
            outline: none;
            border-color: #00ffc8;
            background: rgba(20, 20, 20, 0.95);
            box-shadow: 0 0 20px rgba(0, 255, 200, 0.15);
        }

        input[type="text"]::placeholder {
            color: #555555;
        }

        .controls-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-base {
            padding: 16px 20px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-base::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
        }

        .btn-base:hover::before {
            left: 100%;
        }

        .btn-start {
            background: linear-gradient(135deg, #00ffc8 0%, #00e6b5 100%);
            color: #000000;
            box-shadow: 0 4px 15px rgba(0, 255, 200, 0.3);
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 255, 200, 0.4);
        }

        .btn-start:active {
            transform: translateY(0);
        }

        .btn-start:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-pause {
            background: linear-gradient(135deg, #ffa500 0%, #ff8c00 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
        }

        .btn-pause:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(255, 165, 0, 0.4);
        }

        .btn-pause:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-stop {
            background: linear-gradient(135deg, #ff4444 0%, #ff2222 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(255, 68, 68, 0.3);
        }

        .btn-stop:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(255, 68, 68, 0.4);
        }

        .btn-stop:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-clean {
            background: linear-gradient(135deg, #666666 0%, #555555 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(102, 102, 102, 0.3);
        }

        .btn-clean:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 102, 102, 0.4);
        }

        .results-section {
            margin-top: 30px;
        }

        .result-card {
            background: rgba(15, 15, 15, 0.6);
            border: 1px solid rgba(0, 255, 200, 0.1);
            border-radius: 12px;
            margin-bottom: 14px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .result-card:hover {
            border-color: rgba(0, 255, 200, 0.2);
            box-shadow: 0 4px 20px rgba(0, 255, 200, 0.1);
        }

        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .result-header:hover {
            background: rgba(0, 255, 200, 0.02);
        }

        .result-title {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 15px;
            font-weight: 600;
            flex: 1;
        }

        .result-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .result-icon.charged {
            color: #00a8ff;
            background: rgba(0, 168, 255, 0.15);
            box-shadow: 0 2px 10px rgba(0, 168, 255, 0.2);
        }

        .result-icon.live {
            color: #00ff88;
            background: rgba(0, 255, 136, 0.15);
            box-shadow: 0 2px 10px rgba(0, 255, 136, 0.2);
        }

        .result-icon.dead {
            color: #ff5555;
            background: rgba(255, 85, 85, 0.15);
            box-shadow: 0 2px 10px rgba(255, 85, 85, 0.2);
        }

        .result-header:hover .result-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .result-count {
            background: rgba(0, 255, 200, 0.1);
            color: #00ffc8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            min-width: 35px;
            text-align: center;
            border: 1px solid rgba(0, 255, 200, 0.2);
        }

        .result-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            background: linear-gradient(135deg, #00ffc8 0%, #00e6b5 100%);
            border: none;
            border-radius: 8px;
            color: #000000;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0, 255, 200, 0.2);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 255, 200, 0.3);
        }

        .btn-action.delete {
            background: linear-gradient(135deg, #ff5555 0%, #ff3333 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(255, 85, 85, 0.2);
        }

        .btn-action.delete:hover {
            box-shadow: 0 4px 12px rgba(255, 85, 85, 0.3);
        }

        .btn-chevron {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666666;
            transition: transform 0.3s ease;
            font-size: 14px;
        }

        .result-card.expanded .btn-chevron {
            transform: rotate(180deg);
            color: #00ffc8;
        }

        .result-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .result-card.expanded .result-content {
            max-height: 450px;
        }

        .result-list {
            padding: 0 22px 18px 22px;
            max-height: 400px;
            overflow-y: auto;
        }

        .result-item {
            background: rgba(20, 20, 20, 0.8);
            border-left: 3px solid;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #cccccc;
            animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transition: all 0.2s ease;
            backdrop-filter: blur(5px);
        }

        .result-item:hover {
            background: rgba(25, 25, 25, 0.9);
            transform: translateX(4px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-12px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .result-item.charged {
            border-left-color: #00a8ff;
            background: rgba(0, 168, 255, 0.05);
        }

        .result-item.live {
            border-left-color: #00ff88;
            background: rgba(0, 255, 136, 0.05);
        }

        .result-item.dead {
            border-left-color: #ff5555;
            background: rgba(255, 85, 85, 0.05);
        }

        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: #555555;
            font-size: 13px;
        }

        .empty-state i {
            font-size: 32px;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        .footer {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.4) 100%);
            border-top: 1px solid rgba(0, 255, 200, 0.1);
            padding: 24px 20px;
            margin-top: auto;
            backdrop-filter: blur(10px);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-text {
            font-size: 12px;
            color: #888888;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-text a {
            color: #00ffc8;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .footer-text a:hover {
            color: #00e6b5;
            text-shadow: 0 0 10px rgba(0, 255, 200, 0.3);
        }

        .footer-badge {
            background: rgba(0, 255, 200, 0.1);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            color: #00ffc8;
            border: 1px solid rgba(0, 255, 200, 0.2);
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .container {
                padding: 20px 16px;
            }

            .header-title {
                font-size: 28px;
            }

            .controls-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0;
            }

            .header {
                padding: 30px 16px;
            }

            .header-title {
                font-size: 28px;
                letter-spacing: 1px;
                gap: 12px;
            }
            
            .header-title i {
                font-size: 26px;
            }
            
            .header-subtitle {
                font-size: 12px;
            }

            .container {
                padding: 24px 16px;
            }

            .controls-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .result-header {
                padding: 14px 16px;
            }

            .result-list {
                padding: 0 16px 14px 16px;
            }

            .result-title {
                gap: 10px;
                font-size: 14px;
            }

            .result-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .result-actions {
                gap: 6px;
            }

            .btn-action {
                padding: 6px 10px;
                font-size: 11px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-text {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 24px 12px;
            }
            
            .header-title {
                font-size: 22px;
                gap: 10px;
            }
            
            .header-title i {
                font-size: 20px;
            }

            .header-subtitle {
                font-size: 11px;
            }

            .section-label {
                font-size: 12px;
            }

            .result-count {
                font-size: 12px;
            }

            .btn-base {
                padding: 14px 12px;
                font-size: 12px;
                gap: 6px;
            }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .fa-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <i class="fa-solid fa-microchip"></i> PladixCentral - Checker Plus
            </div>
            <div class="header-subtitle">
                Usuário: pladixoficial | Expiração: 01/01/2029 - 16:27:49
            </div>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <label class="section-label">
                <i class="fa-solid fa-credit-card"></i> Lista de Cartões
            </label>
            <textarea id="cardList" placeholder="Formato: xxxxxxxx|xx|xx|xxx (um cartão por linha)"></textarea>
        </div>

        <div class="section">
            <label class="section-label">
                <i class="fa-solid fa-plug"></i> Selecionar Gateway/API
            </label>
            <select id="gateSelect">
                <option value="">-- Escolha uma API --</option>
                <?php foreach ($apis as $api): ?>
                    <option value="<?php echo htmlspecialchars($api['file']); ?>"><?php echo htmlspecialchars($api['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="section">
            <label class="section-label">
                <i class="fa-solid fa-key"></i> Chave Stripe (Opcional)
            </label>
            <input type="text" id="stripeKey" placeholder="sk_live_xxxxxxxxxxxxxxxx">
        </div>

        <div class="controls-grid">
            <button class="btn-base btn-start" id="btnStart" disabled>
                <i class="fa-solid fa-play"></i>
                INICIAR
            </button>
            <button class="btn-base btn-pause" id="btnPause" disabled>
                <i class="fa-solid fa-pause"></i>
                PAUSAR
            </button>
            <button class="btn-base btn-stop" id="btnStop" disabled>
                <i class="fa-solid fa-stop"></i>
                PARAR
            </button>
            <button class="btn-base btn-clean" id="btnClean">
                <i class="fa-solid fa-trash-can"></i>
                LIMPAR
            </button>
        </div>

        <div class="results-section">
            <div class="result-card" id="chargedCard">
                <div class="result-header" onclick="toggleCard('charged')">
                    <div class="result-title">
                        <div class="result-icon charged">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span>Cartões Aprovados</span>
                        <span class="result-count" id="chargedCount">0</span>
                    </div>
                    <div class="result-actions">
                        <button class="btn-action btn-copy-charged" title="Copiar todos">
                            <i class="fa-solid fa-copy"></i>
                            Copiar
                        </button>
                        <div class="btn-chevron">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="result-content">
                    <div class="result-list" id="chargedList">
                        <div class="empty-state">
                            <div><i class="fa-solid fa-inbox"></i></div>
                            Nenhum cartão aprovado ainda
                        </div>
                    </div>
                </div>
            </div>

            <div class="result-card" id="liveCard">
                <div class="result-header" onclick="toggleCard('live')">
                    <div class="result-title">
                        <div class="result-icon live">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span>Cartões Válidos</span>
                        <span class="result-count" id="liveCount">0</span>
                    </div>
                    <div class="result-actions">
                        <button class="btn-action btn-copy-live" title="Copiar todos">
                            <i class="fa-solid fa-copy"></i>
                            Copiar
                        </button>
                        <div class="btn-chevron">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="result-content">
                    <div class="result-list" id="liveList">
                        <div class="empty-state">
                            <div><i class="fa-solid fa-inbox"></i></div>
                            Nenhum cartão válido ainda
                        </div>
                    </div>
                </div>
            </div>

            <div class="result-card" id="deadCard">
                <div class="result-header" onclick="toggleCard('dead')">
                    <div class="result-title">
                        <div class="result-icon dead">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <span>Cartões Inválidos</span>
                        <span class="result-count" id="deadCount">0</span>
                    </div>
                    <div class="result-actions">
                        <button class="btn-action delete" onclick="deleteAll(); event.stopPropagation();" title="Deletar todos">
                            <i class="fa-solid fa-trash"></i>
                            Deletar
                        </button>
                        <div class="btn-chevron">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="result-content">
                    <div class="result-list" id="deadList">
                        <div class="empty-state">
                            <div><i class="fa-solid fa-inbox"></i></div>
                            Nenhum cartão inválido ainda
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <div class="footer-text">
                <i class="fa-solid fa-copyright"></i>
                <?php echo $currentYear; ?> <strong>PladixCentral</strong> - Todos os direitos reservados.
            </div>
            <div class="footer-badge">
                <i class="fa-solid fa-rocket"></i> PladixCentral - Plus v20.26
            </div>
            <div class="footer-text">
                Desenvolvido com <i class="fa-solid fa-heart" style="color: #ff5555;"></i> por <strong>Pladix Team</strong>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
    <style>
        #toast-container > div {
            opacity: 1 !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 12px !important;
            padding: 16px 20px !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            min-height: 60px !important;
        }
        
        #toast-container > div:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6) !important;
        }
        
        #toast-container > .toast-success {
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 200, 100, 0.2) 100%) !important;
            border: 1px solid rgba(0, 255, 136, 0.5) !important;
            color: #00ff88 !important;
        }
        
        #toast-container > .toast-success:before {
            content: '\f058' !important;
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
            font-size: 24px !important;
            color: #00ff88 !important;
            position: absolute !important;
            left: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        
        #toast-container > .toast-error {
            background: linear-gradient(135deg, rgba(255, 85, 85, 0.2) 0%, rgba(200, 50, 50, 0.2) 100%) !important;
            border: 1px solid rgba(255, 85, 85, 0.5) !important;
            color: #ff5555 !important;
        }
        
        #toast-container > .toast-error:before {
            content: '\f06a' !important;
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
            font-size: 24px !important;
            color: #ff5555 !important;
            position: absolute !important;
            left: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        
        #toast-container > .toast-warning {
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.2) 0%, rgba(200, 120, 0, 0.2) 100%) !important;
            border: 1px solid rgba(255, 165, 0, 0.5) !important;
            color: #ffa500 !important;
        }
        
        #toast-container > .toast-warning:before {
            content: '\f071' !important;
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
            font-size: 24px !important;
            color: #ffa500 !important;
            position: absolute !important;
            left: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        
        #toast-container > .toast-info {
            background: linear-gradient(135deg, rgba(0, 168, 255, 0.2) 0%, rgba(0, 120, 200, 0.2) 100%) !important;
            border: 1px solid rgba(0, 168, 255, 0.5) !important;
            color: #00a8ff !important;
        }
        
        #toast-container > .toast-info:before {
            content: '\f05a' !important;
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
            font-size: 24px !important;
            color: #00a8ff !important;
            position: absolute !important;
            left: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        
        .toast-success .toast-icon,
        .toast-error .toast-icon,
        .toast-warning .toast-icon,
        .toast-info .toast-icon {
            display: none !important;
        }
        
        #toast-container .toast-message {
            padding-left: 40px !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            color: inherit !important;
        }
        
        #toast-container .toast-title {
            padding-left: 40px !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            margin-bottom: 4px !important;
            color: inherit !important;
        }
        
        #toast-container > div .toast-close-button {
            color: inherit !important;
            opacity: 0.7 !important;
            font-size: 18px !important;
            font-weight: 700 !important;
        }
        
        #toast-container > div .toast-close-button:hover {
            opacity: 1 !important;
        }
        
        #toast-container > div .toast-progress {
            opacity: 0.4 !important;
            height: 4px !important;
        }
        
        #toast-container > .toast-success .toast-progress {
            background: #00ff88 !important;
        }
        
        #toast-container > .toast-error .toast-progress {
            background: #ff5555 !important;
        }
        
        #toast-container > .toast-warning .toast-progress {
            background: #ffa500 !important;
        }
        
        #toast-container > .toast-info .toast-progress {
            background: #00a8ff !important;
        }
        
        .swal2-popup {
            background: rgba(20, 20, 20, 0.98) !important;
            border: 1px solid rgba(0, 255, 200, 0.3) !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 32px rgba(0, 255, 200, 0.3) !important;
            backdrop-filter: blur(10px) !important;
        }
        
        .swal2-title {
            color: #ffffff !important;
            font-size: 24px !important;
            font-weight: 700 !important;
        }
        
        .swal2-html-container {
            color: #cccccc !important;
            font-size: 14px !important;
        }
        
        .swal2-confirm {
            background: linear-gradient(135deg, #ff5555 0%, #ff3333 100%) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 12px 30px !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(255, 85, 85, 0.3) !important;
        }
        
        .swal2-cancel {
            background: linear-gradient(135deg, #666666 0%, #555555 100%) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 12px 30px !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(102, 102, 102, 0.3) !important;
        }
        
        .swal2-icon.swal2-warning {
            border-color: #ffa500 !important;
            color: #ffa500 !important;
        }
    </style>
    <script>
        let stats = { charged: 0, live: 0, dead: 0 };
        let checking = false;
        let stopped = true;
        let paused = false;
        let tested = 0;
        let total = 0;

        document.getElementById('gateSelect').addEventListener('change', function() {
            document.getElementById('btnStart').disabled = !this.value;
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && checking) {
                stopped = true;
                checking = false;
                document.getElementById('btnStart').innerHTML = '<i class="fa-solid fa-play"></i> INICIAR';
                document.getElementById('btnStart').style.background = 'linear-gradient(135deg, #00ffc8 0%, #00e6b5 100%)';
            }
        });

        function updateCounters() {
            document.getElementById('chargedCount').textContent = stats.charged;
            document.getElementById('liveCount').textContent = stats.live;
            document.getElementById('deadCount').textContent = stats.dead;
        }

        function toggleCard(type) {
            const card = document.getElementById(`${type}Card`);
            card.classList.toggle('expanded');
        }

        function addResult(type, cardData) {
            stats[type]++;
            updateCounters();

            const listElement = document.getElementById(`${type}List`);
            const emptyState = listElement.querySelector('.empty-state');
            if (emptyState) {
                emptyState.remove();
            }

            const item = document.createElement('div');
            item.className = `result-item ${type}`;
            item.textContent = cardData;
            listElement.insertBefore(item, listElement.firstChild);

            if (stats[type] === 1) {
                document.getElementById(`${type}Card`).classList.add('expanded');
            }
        }

        function copyResults(type, event) {
            const listElement = document.getElementById(`${type}List`);
            const items = listElement.querySelectorAll('.result-item');

            if (items.length === 0) {
                toastr.warning('Nenhum item para copiar!', 'Aviso');
                return;
            }

            const text = Array.from(items).map(item => item.textContent).join('\n');
            const btn = event.target.closest('.btn-action');
            const originalText = btn.innerHTML;

            function copyToClipboard(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    textArea.style.top = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    return new Promise((resolve, reject) => {
                        try {
                            document.execCommand('copy') ? resolve() : reject();
                            textArea.remove();
                        } catch (err) {
                            textArea.remove();
                            reject(err);
                        }
                    });
                }
            }

            copyToClipboard(text).then(() => {
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copiado!';
                toastr.success(`${items.length} cartão(ões) copiado(s) para a área de transferência!`, 'Sucesso');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
            }).catch((err) => {
                console.error('Erro ao copiar:', err);
                toastr.error('Não foi possível copiar. Tente novamente.', 'Erro');
            });
        }

        function deleteAll() {
            Swal.fire({
                title: 'Confirmar Exclusão',
                text: 'Deseja realmente deletar todos os cartões inválidos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const count = stats.dead;
                    document.getElementById('deadList').innerHTML = '<div class="empty-state"><div><i class="fa-solid fa-inbox"></i></div>Nenhum cartão inválido ainda</div>';
                    stats.dead = 0;
                    updateCounters();
                    toastr.success(`${count} cartão(ões) deletado(s) com sucesso!`, 'Sucesso');
                }
            });
        }

        document.querySelector('.btn-copy-charged').addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            copyResults('charged', e);
        });

        document.querySelector('.btn-copy-charged').addEventListener('touchend', function(e) {
            e.stopPropagation();
            e.preventDefault();
            copyResults('charged', e);
        });

        document.querySelector('.btn-copy-live').addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            copyResults('live', e);
        });

        document.querySelector('.btn-copy-live').addEventListener('touchend', function(e) {
            e.stopPropagation();
            e.preventDefault();
            copyResults('live', e);
        });

        function clearAllResults() {
            ['charged', 'live', 'dead'].forEach(type => {
                document.getElementById(`${type}List`).innerHTML = `<div class="empty-state"><div><i class="fa-solid fa-inbox"></i></div>Nenhum cartão ${type} ainda</div>`;
                stats[type] = 0;
                updateCounters();
            });
        }

        function testCards(card_list, threadCount) {
            if (stopped || paused || tested >= total) {
                if (tested >= total) {
                    document.getElementById('btnStart').innerHTML = '<i class="fa-solid fa-check"></i> CONCLUÍDO';
                    document.getElementById('btnStart').style.background = 'linear-gradient(135deg, #00ff88 0%, #00cc66 100%)';
                    document.getElementById('btnStart').disabled = false;
                    document.getElementById('btnPause').disabled = true;
                    document.getElementById('btnStop').disabled = true;
                    checking = false;
                    stopped = true;
                }
                return;
            }

            let cardsToTest = card_list.slice(tested, tested + threadCount);
            let requests = cardsToTest.map(card => {
                return $.ajax({
                    url: document.getElementById('gateSelect').value,
                    type: 'POST',
                    data: {
                        card: card.trim(),
                        stripe_key: document.getElementById('stripeKey').value.trim()
                    },
                    timeout: 10000
                });
            });

            Promise.allSettled(requests).then(results => {
                if (stopped || paused) return;

                results.forEach((result, index) => {
                    tested++;
                    let card = cardsToTest[index];
                    
                    if (result.status === 'fulfilled') {
                        try {
                            let response = typeof result.value === 'string' ? JSON.parse(result.value) : result.value;
                            
                            if (response.status === 'charged') {
                                addResult('charged', card + ' - ' + response.message);
                            } else if (response.status === 'live') {
                                addResult('live', card + ' - ' + response.message);
                            } else if (response.status === 'dead') {
                                addResult('dead', card + ' - ' + response.message);
                            } else {
                                addResult('dead', card + ' - ⚠ RESPOSTA INVÁLIDA');
                            }
                        } catch (e) {
                            addResult('dead', card + ' - ⚠ ERRO NO PARSE: ' + e.message);
                        }
                    } else {
                        addResult('dead', card + ' - ⚠ ERRO NA API');
                    }
                    updateCounters();
                });

                document.getElementById('cardList').value = card_list.slice(tested).join('\n');

                const progress = Math.round((tested / total) * 100);
                document.getElementById('btnStart').innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> ${progress}%`;

                testCards(card_list, threadCount);
            });
        }

        document.getElementById('btnStart').addEventListener('click', function() {
            const cards = document.getElementById('cardList').value.trim();
            const gate = document.getElementById('gateSelect').value;

            if (!cards) {
                toastr.warning('Por favor, insira uma lista de cartões!', 'Atenção');
                return;
            }

            if (!gate) {
                toastr.warning('Por favor, selecione uma API!', 'Atenção');
                return;
            }

            if (checking) {
                toastr.info('Já está verificando cartões!', 'Informação');
                return;
            }

            const cardList = cards.split('\n').filter(c => c.trim());
            total = cardList.length;
            tested = 0;
            stats = { charged: 0, live: 0, dead: 0 };

            stopped = false;
            paused = false;
            checking = true;

            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> VERIFICANDO...';
            this.style.background = 'linear-gradient(135deg, #ffa500 0%, #ff8c00 100%)';
            this.disabled = true;

            document.getElementById('btnPause').disabled = false;
            document.getElementById('btnStop').disabled = false;

            if (tested === 0) {
                clearAllResults();
            }

            testCards(cardList, 2);
        });

        document.getElementById('btnPause').addEventListener('click', function() {
            paused = true;
            document.getElementById('btnStart').disabled = false;
            document.getElementById('btnPause').disabled = true;
            document.getElementById('btnStart').innerHTML = '<i class="fa-solid fa-play"></i> CONTINUAR';
            document.getElementById('btnStart').style.background = 'linear-gradient(135deg, #00ffc8 0%, #00e6b5 100%)';
        });

        document.getElementById('btnStop').addEventListener('click', function() {
            stopped = true;
            checking = false;
            document.getElementById('btnStart').disabled = false;
            document.getElementById('btnPause').disabled = true;
            document.getElementById('btnStop').disabled = true;
            document.getElementById('btnStart').innerHTML = '<i class="fa-solid fa-play"></i> INICIAR';
            document.getElementById('btnStart').style.background = 'linear-gradient(135deg, #00ffc8 0%, #00e6b5 100%)';
        });

        document.getElementById('btnClean').addEventListener('click', function() {
            tested = total = 0;
            stats = { charged: 0, live: 0, dead: 0 };
            stopped = true;
            checking = false;
            updateCounters();
            document.getElementById('cardList').value = '';
            clearAllResults();
            document.getElementById('btnStart').disabled = false;
            document.getElementById('btnPause').disabled = true;
            document.getElementById('btnStop').disabled = true;
            document.getElementById('btnStart').innerHTML = '<i class="fa-solid fa-play"></i> INICIAR';
            document.getElementById('btnStart').style.background = 'linear-gradient(135deg, #00ffc8 0%, #00e6b5 100%)';
        });
    </script>
</body>
</html>
