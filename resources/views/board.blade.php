<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1100100 Devs Dijieron — Tablero</title>
    @vite('resources/css/board.css')
    <style>
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
        
        /* Perfect Round Animation Styles */
        .perfect-round-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.9);
            animation: perfectFadeIn 0.5s ease-out;
        }
        
        .perfect-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.3) 0%, rgba(255, 140, 0, 0.2) 50%, transparent 100%);
            animation: perfectPulse 2s ease-in-out infinite;
        }
        
        .perfect-content {
            text-align: center;
            z-index: 10;
            animation: perfectSlideUp 0.8s ease-out;
        }
        
        .perfect-icon {
            font-size: 120px;
            margin-bottom: 20px;
            animation: perfectSpin 2s ease-in-out infinite;
            filter: drop-shadow(0 0 30px rgba(255, 215, 0, 0.8));
        }
        
        .perfect-title {
            font-size: 72px;
            font-weight: 900;
            color: #FFD700;
            text-shadow: 0 0 30px rgba(255, 215, 0, 0.8), 0 0 60px rgba(255, 215, 0, 0.5);
            margin: 0 0 20px 0;
            animation: perfectGlow 1.5s ease-in-out infinite alternate;
        }
        
        .perfect-team {
            font-size: 36px;
            font-weight: 700;
            color: #FFF;
            margin: 0 0 15px 0;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
        }
        
        .perfect-message {
            font-size: 28px;
            font-weight: 600;
            color: #FFE55C;
            margin: 0 0 20px 0;
        }
        
        .perfect-bonus {
            font-size: 32px;
            font-weight: 800;
            color: #00FF7F;
            text-shadow: 0 0 25px rgba(0, 255, 127, 0.7);
            animation: perfectBounce 1s ease-in-out infinite;
        }
        
        .perfect-particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }
        
        .perfect-rings {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        /* ===== ANIMACIONES MEJORADAS ===== */
        
        /* Transición dramática entre equipos */
        .team-transition {
            position: relative;
            overflow: hidden;
        }
        
        .team-transition::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 215, 0, 0.4), 
                rgba(255, 215, 0, 0.8), 
                rgba(255, 215, 0, 0.4), 
                transparent
            );
            z-index: 2;
            animation: teamSweep 1.5s ease-in-out;
        }
        
        @keyframes teamSweep {
            0% { left: -100%; }
            50% { left: 0%; }
            100% { left: 100%; }
        }
        
        .team-pulse {
            animation: teamPulse 0.6s ease-in-out;
        }
        
        @keyframes teamPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(255, 215, 0, 0.3); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 215, 0, 0); }
        }
        
        /* Efectos de partículas para respuestas altas */
        .high-score-particles {
            position: absolute;
            pointer-events: none;
            z-index: 10;
        }
        
        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: particleFloat 2s ease-out forwards;
        }
        
        @keyframes particleFloat {
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateY(-100px) scale(0.3);
            }
        }
        
        .particle.gold { background: #FFD700; }
        .particle.orange { background: #FFA500; }
        .particle.yellow { background: #FFFF00; }
        
        /* Animación shake para X's */
        .shake-animation {
            animation: shakeError 0.8s ease-in-out;
        }
        
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
            20%, 40%, 60%, 80% { transform: translateX(8px); }
        }
        
        .strike-flash {
            animation: strikeFlash 0.5s ease-in-out;
        }
        
        @keyframes strikeFlash {
            0%, 100% { background-color: transparent; }
            50% { background-color: rgba(255, 0, 0, 0.3); }
        }
        
        @keyframes pointsEffect {
            0% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -70px) scale(1.2); }
            100% { opacity: 0; transform: translate(-50%, -100px) scale(0.8); }
        }
        
        @keyframes finishPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
        }
        
        /* ===== DINERO RÁPIDO STYLES ===== */
        
        .fast-money-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .fast-money-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
        }
        
        .fast-money-container {
            position: relative;
            width: 95%;
            max-width: 1200px;
            background: rgba(11, 12, 16, 0.95);
            border-radius: 16px;
            border: 2px solid #f59e0b;
            box-shadow: 0 0 50px rgba(245, 158, 11, 0.3);
            padding: 30px;
        }
        
        .fast-money-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .fast-money-title {
            font-size: 36px;
            font-weight: 900;
            color: #f59e0b;
            text-shadow: 0 0 20px rgba(245, 158, 11, 0.6);
            margin: 0;
            animation: fastMoneyGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes fastMoneyGlow {
            0% { text-shadow: 0 0 20px rgba(245, 158, 11, 0.6); }
            100% { text-shadow: 0 0 30px rgba(245, 158, 11, 0.8), 0 0 40px rgba(245, 158, 11, 0.4); }
        }
        
        .fast-money-target {
            font-size: 18px;
            color: #66fcf1;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .fast-money-exit {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 20px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .fast-money-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        /* Puntuación total centrada */
        .fast-money-score {
            text-align: center;
            background: rgba(102, 252, 241, 0.1);
            border-radius: 12px;
            padding: 15px;
            border: 2px solid #66fcf1;
        }
        
        .total-score-display {
            font-size: 36px;
            font-weight: 900;
            color: #f59e0b;
            text-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
        }
        
        /* Contenedor de dos participantes */
        .participants-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .participant-column {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 2px solid rgba(102, 252, 241, 0.3);
        }
        
        .participant-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(102, 252, 241, 0.3);
        }
        
        .participant-header h3 {
            font-size: 20px;
            font-weight: bold;
            color: #66fcf1;
            margin: 0 0 8px 0;
        }
        
        .participant-score {
            font-size: 16px;
            font-weight: bold;
            color: #f59e0b;
        }
        
        .participant-answers {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .answer-row {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: rgba(102, 252, 241, 0.05);
            border: 1px solid rgba(102, 252, 241, 0.2);
            border-radius: 8px;
            transition: all 0.3s ease;
            min-height: 50px;
        }
        
        .answer-row.revealed {
            background: rgba(245, 158, 11, 0.1);
            border-color: #f59e0b;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.2);
        }
        
        .answer-number {
            font-size: 18px;
            font-weight: bold;
            color: #66fcf1;
            min-width: 25px;
        }
        
        .answer-content {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .answer-text {
            font-size: 16px;
            font-weight: bold;
            color: #c5c6c7;
            flex: 1;
        }
        
        .answer-points {
            font-size: 18px;
            font-weight: 900;
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            padding: 4px 10px;
            border-radius: 6px;
            min-width: 50px;
            text-align: center;
        }
        
        /* Estados especiales */
        .answer-row.blocked {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
            opacity: 0.6;
        }
        
        .answer-row.hidden {
            opacity: 0.3;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .participants-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .fast-money-container {
                width: 98%;
                padding: 20px;
            }
            
            .participant-header h3 {
                font-size: 18px;
            }
            
            .answer-text {
                font-size: 14px;
            }
            
            .answer-points {
                font-size: 16px;
                padding: 3px 8px;
            }
        }
        
        .status-message {
            text-align: center;
            font-size: 16px;
            color: #66fcf1;
            margin-top: 20px;
            font-weight: bold;
        }
        
        .perfect-ring {
            position: absolute;
            border: 3px solid rgba(255, 215, 0, 0.6);
            border-radius: 50%;
            animation: perfectRingExpand 2s ease-out infinite;
        }
        
        .perfect-particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, #FFD700, #FFA500);
            border-radius: 50%;
            animation: perfectParticleFly 3s ease-out infinite;
        }
        
        @keyframes perfectFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes perfectSlideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes perfectSpin {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
        }
        
        @keyframes perfectGlow {
            0% { text-shadow: 0 0 30px rgba(255, 215, 0, 0.8), 0 0 60px rgba(255, 215, 0, 0.5); }
            100% { text-shadow: 0 0 50px rgba(255, 215, 0, 1), 0 0 80px rgba(255, 215, 0, 0.7); }
        }
        
        @keyframes perfectPulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.05); opacity: 0.5; }
        }
        
        @keyframes perfectBounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.05); }
        }
        
        @keyframes perfectRingExpand {
            0% { width: 50px; height: 50px; opacity: 0.8; }
            100% { width: 400px; height: 400px; opacity: 0; }
        }
        
        @keyframes perfectParticleFly {
            0% { 
                transform: translate(0, 0) scale(0); 
                opacity: 1; 
            }
            50% { 
                transform: translate(var(--random-x), var(--random-y)) scale(1); 
                opacity: 0.8; 
            }
            100% { 
                transform: translate(calc(var(--random-x) * 2), calc(var(--random-y) * 2)) scale(0); 
                opacity: 0; 
            }
        }
        
        @keyframes totalReveal {
            0% { 
                transform: translate(-50%, -50%) scale(0); 
                opacity: 0; 
            }
            20% { 
                transform: translate(-50%, -50%) scale(1.2); 
                opacity: 1; 
            }
            100% { 
                transform: translate(-50%, -50%) scale(1); 
                opacity: 0; 
            }
        }
        
        /* 🎉 Animaciones de Victoria y Derrota */
        .victory-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #10b981, #34d399, #fbbf24, #f59e0b);
            background-size: 400% 400%;
            animation: victoryBackground 3s ease-in-out infinite;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
        }
        
        .defeat-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #dc2626, #ef4444, #374151, #6b7280);
            background-size: 400% 400%;
            animation: defeatBackground 2s ease-in-out;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
        }
        
        .victory-content {
            text-align: center;
            color: white;
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 0 0 30px rgba(0,0,0,0.8);
            animation: victoryText 3s ease-in-out;
        }
        
        .defeat-content {
            text-align: center;
            color: white;
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 0 0 30px rgba(0,0,0,0.8);
            animation: defeatText 2s ease-in-out;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #fbbf24;
            animation: confettiFall 3s linear infinite;
        }
        
        .confetti:nth-child(2n) { background: #10b981; animation-delay: -0.5s; }
        .confetti:nth-child(3n) { background: #ef4444; animation-delay: -1s; }
        .confetti:nth-child(4n) { background: #3b82f6; animation-delay: -1.5s; }
        .confetti:nth-child(5n) { background: #8b5cf6; animation-delay: -2s; }
        
        @keyframes victoryBackground {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes defeatBackground {
            0% { background-position: 0% 50%; opacity: 0; }
            50% { background-position: 100% 50%; opacity: 0.9; }
            100% { background-position: 0% 50%; opacity: 0; }
        }
        
        @keyframes victoryText {
            0% { transform: scale(0) rotate(-180deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(0deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        
        @keyframes defeatText {
            0% { transform: translateY(-100px); opacity: 0; }
            50% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(0); opacity: 0.8; }
        }
        
        @keyframes confettiFall {
            0% { transform: translateY(-100vh) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(720deg); }
        }
        
        .show-victory {
            animation: showOverlay 4s ease-in-out forwards;
        }
        
        .show-defeat {
            animation: showOverlay 3s ease-in-out forwards;
        }
        
        @keyframes showOverlay {
            0% { opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="dashboard">
        <div class="board-wrapper">
        <div class="board" style="position:relative">
            <!-- Estado in absolute top right corner of entire board -->
            <div style="position:absolute;top:20px;right:20px;z-index:100">
                <div class="status">Estado: <strong id="state">Esperando</strong></div>
            </div>
            
            <div class="header" style="align-items:flex-start;gap:12px;flex-direction:column;display:flex">
                <div style="display:flex;align-items:center;gap:18px;width:100%">
                    <!-- left score - Team 1 -->
                    <div style="flex:0 0 160px;display:flex;flex-direction:column;align-items:center">
                        <div id="team1Card" class="score team-card" style="width:160px;padding:14px">
                            <div class="num" id="team1Score">000</div>
                            <div class="lbl" id="team1Name">Equipo 1</div>
                        </div>
                    </div>

                    <div style="flex:1">
                        <!-- Title centered -->
                        <div style="text-align:center;margin-bottom:8px">
                            <div class="title">1100100 Devs Dijieron</div>
                        </div>

                        <!-- Big accumulated points display - MOVED TO TOP -->
                        <div style="margin-top:12px;margin-bottom:12px;padding:20px;border-radius:12px;background:linear-gradient(135deg, rgba(16,185,129,0.15) 0%, rgba(5,150,105,0.15) 100%);border:2px solid rgba(16,185,129,0.4);text-align:center;box-shadow:0 0 30px rgba(16,185,129,0.3)">
                            <div style="font-size:14px;color:#6ee7b7;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">💰 PUNTOS EN JUEGO</div>
                            <div id="roundPointsDisplay" style="font-size:64px;font-weight:900;color:#10b981;text-shadow:0 0 25px rgba(16,185,129,0.6), 0 0 50px rgba(16,185,129,0.3);line-height:1">0</div>
                        </div>

                        <!-- Question placed prominently above the board -->
                        <div style="margin-bottom:6px;padding:12px;border-radius:10px;background:linear-gradient(90deg, rgba(0,229,255,0.02), rgba(123,97,255,0.02));border:1px solid rgba(0,229,255,0.06);font-weight:700;color:#dffaff"> 
                            <span style="color:var(--muted);font-weight:600;margin-right:8px">Pregunta:</span>
                            <span id="question">(esperando)</span>
                        </div>
                        
                        <!-- Round info and multiplier -->
                        <div style="margin-top:6px;margin-bottom:10px;padding:10px;border-radius:8px;background:rgba(0,0,0,0.04);color:#cfeffb;font-weight:700;display:flex;align-items:center;justify-content:center;gap:12px">
                            <span id="roundNumberDisplay" style="color:var(--accent);font-size:16px">Ronda: 1</span>
                            <span id="multiplierDisplay" style="color:#fbbf24;font-weight:900;font-size:16px"></span>
                        </div>

                        <!-- Active Team Banner -->
                        <!-- Active Team Banner (reemplazado por highlight en tarjetas, mantenido oculto por compatibilidad) -->
                        <div id="activeTurnBanner" style="display:none"></div>

                        <!-- Strikes (X's) Display - Horizontal -->
                        <div id="strikesDisplay" style="margin-top:10px;margin-bottom:10px;display:flex;gap:15px;justify-content:center;align-items:center;flex-direction:row;">
                            <!-- X's rendered here by JS -->
                        </div>
                    </div>

                    <!-- right score - Team 2 -->
                    <div style="flex:0 0 160px;display:flex;flex-direction:column;align-items:center">
                        <div id="team2Card" class="score team-card" style="width:160px;padding:14px">
                            <div class="num" id="team2Score">000</div>
                            <div class="lbl" id="team2Name">Equipo 2</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:8px;display:block"> 
                <div class="answers-grid" id="answers">
                    <!-- cells rendered here -->
                </div>
            </div>

        </div>
        <!-- Terminal de estado en la parte inferior -->
        <div id="terminalStatus" style="position:fixed;bottom:0;left:0;right:0;background:#0a0a0a;color:#00ff00;font-family:'Fira Code','Courier New',monospace;font-size:11px;padding:6px 12px;border-top:1px solid #333;z-index:1000;display:flex;align-items:center;gap:8px;">
            <span style="color:#66fcf1;">➜</span>
            <span style="color:#ffffff;">game-board@1100100devs:</span>
            <span style="color:#66fcf1;">~$</span>
            <span id="connType" style="color:#00ff00;">conectando...</span>
            <span id="terminalCursor" style="color:#00ff00;animation:blink 1s infinite;">▋</span>
        </div>
    </div>
</div>

<div class="bg-grid" aria-hidden>
    <!-- decorative, no semantics -->
</div>

<!-- Steal overlay (centered) -->
<div id="stealOverlay" class="steal-overlay">
    <div id="stealCard" class="steal-card" style="animation:steal-pop .35s ease-out, steal-glow 1.2s ease-in-out infinite alternate">
        <div class="steal-title">¡Robo de puntos!</div>
        <div id="stealOverlaySub" class="steal-sub"></div>
    </div>
</div>

<!-- Countdown overlay (deprecated - usando telón teatral) -->
<!-- <div id="countdownOverlay" style="display:none;">...</div> -->

<!-- Teatro Telón para cambio de rondas -->
<div id="curtainOverlay" class="curtain-overlay" style="display:none;">
    <div class="curtain-left"></div>
    <div class="curtain-right"></div>
    <div id="roundTransitionMessage" class="round-transition-message">
        <h1 id="roundTransitionTitle">RONDA 2</h1>
        <p id="roundTransitionSubtitle">¡Preparando nueva ronda!</p>
    </div>
</div>

<!-- Animación del Ganador -->
<div id="winnerOverlay" class="winner-overlay" style="display:none;">
    <div class="winner-background"></div>
    <div class="winner-content">
        <div class="winner-trophy">🏆</div>
        <h1 id="winnerTitle" class="winner-title">¡GANADOR!</h1>
        <h2 id="winnerTeam" class="winner-team">Equipo Ganador</h2>
        <div id="winnerScore" class="winner-score">000 puntos</div>
        <div class="winner-message">¡Felicitaciones por la victoria!</div>
    </div>
    <div class="confetti-container" id="confettiContainer"></div>
    <div class="fireworks-container" id="fireworksContainer"></div>
</div>

<!-- Animación de Ronda Perfecta -->
<div id="perfectRoundOverlay" class="perfect-round-overlay" style="display:none;">
    <div class="perfect-background"></div>
    <div class="perfect-content">
        <div class="perfect-icon">⭐</div>
        <h1 class="perfect-title">¡RONDA PERFECTA!</h1>
        <h2 id="perfectTeam" class="perfect-team">Equipo</h2>
        <div class="perfect-message">¡Todas las respuestas correctas!</div>
    </div>
    <div class="perfect-particles" id="perfectParticles"></div>
    <div class="perfect-rings" id="perfectRings"></div>
</div>

<!-- Modo Dinero Rápido -->
<div id="fastMoneyOverlay" class="fast-money-overlay" style="display:none;">
    <div class="fast-money-background"></div>
    <div class="fast-money-container">
        <div class="fast-money-header">
            <h1 class="fast-money-title">💰 DINERO RÁPIDO</h1>
            <div class="fast-money-target">META: 200 PUNTOS</div>
            <button id="fastMoneyExit" class="fast-money-exit">× Salir</button>
        </div>
        
        <div class="fast-money-content">
            <!-- Puntuación total centrada -->
            <div class="fast-money-score">
                <div id="fastMoneyTotalScore" class="total-score-display">META: 200 PUNTOS</div>
            </div>
            
            <!-- Dos columnas para participantes -->
            <div class="participants-container">
                <!-- Participante 1 -->
                <div class="participant-column">
                    <div class="participant-header">
                        <h3>🎮 PARTICIPANTE 1</h3>
                        <div class="participant-score">Score: <span id="participant1Score">0</span></div>
                    </div>
                    <div class="participant-answers">
                        <div class="answer-row" data-fm-answer="0" data-participant="1">
                            <div class="answer-number">1.</div>
                            <div class="answer-content">
                                <div id="fmAnswer1P1" class="answer-text">---</div>
                                <div id="fmPoints1P1" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="1" data-participant="1">
                            <div class="answer-number">2.</div>
                            <div class="answer-content">
                                <div id="fmAnswer2P1" class="answer-text">---</div>
                                <div id="fmPoints2P1" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="2" data-participant="1">
                            <div class="answer-number">3.</div>
                            <div class="answer-content">
                                <div id="fmAnswer3P1" class="answer-text">---</div>
                                <div id="fmPoints3P1" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="3" data-participant="1">
                            <div class="answer-number">4.</div>
                            <div class="answer-content">
                                <div id="fmAnswer4P1" class="answer-text">---</div>
                                <div id="fmPoints4P1" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="4" data-participant="1">
                            <div class="answer-number">5.</div>
                            <div class="answer-content">
                                <div id="fmAnswer5P1" class="answer-text">---</div>
                                <div id="fmPoints5P1" class="answer-points">--</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Participante 2 -->
                <div class="participant-column">
                    <div class="participant-header">
                        <h3>🎮 PARTICIPANTE 2</h3>
                        <div class="participant-score">Score: <span id="participant2Score">0</span></div>
                    </div>
                    <div class="participant-answers">
                        <div class="answer-row" data-fm-answer="0" data-participant="2">
                            <div class="answer-number">1.</div>
                            <div class="answer-content">
                                <div id="fmAnswer1P2" class="answer-text">---</div>
                                <div id="fmPoints1P2" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="1" data-participant="2">
                            <div class="answer-number">2.</div>
                            <div class="answer-content">
                                <div id="fmAnswer2P2" class="answer-text">---</div>
                                <div id="fmPoints2P2" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="2" data-participant="2">
                            <div class="answer-number">3.</div>
                            <div class="answer-content">
                                <div id="fmAnswer3P2" class="answer-text">---</div>
                                <div id="fmPoints3P2" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="3" data-participant="2">
                            <div class="answer-number">4.</div>
                            <div class="answer-content">
                                <div id="fmAnswer4P2" class="answer-text">---</div>
                                <div id="fmPoints4P2" class="answer-points">--</div>
                            </div>
                        </div>
                        <div class="answer-row" data-fm-answer="4" data-participant="2">
                            <div class="answer-number">5.</div>
                            <div class="answer-content">
                                <div id="fmAnswer5P2" class="answer-text">---</div>
                                <div id="fmPoints5P2" class="answer-points">--</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="fast-money-status">
                <div id="fastMoneyMessage" class="status-message">¡Listo para comenzar!</div>
            </div>
        </div>
    </div>
</div>

<script>
// Keep console active for debugging connection issues
// try{ console.log = function(){}; console.debug = function(){}; }catch(e){}
// Keep the same messaging contract: BroadcastChannel primary, localStorage fallback
let channel = null; let usingBroadcast = false;
try{ 
    if (typeof BroadcastChannel !== 'undefined'){ 
        channel = new BroadcastChannel('game-100mx'); 
        usingBroadcast = true; 
        document.getElementById('connType').textContent = 'broadcastchannel --status=connected ✓'; 
        console.log('[board] BroadcastChannel conectado exitosamente'); 
    } 
}catch(e){ 
    console.error('[board] Error BroadcastChannel:', e); 
    document.getElementById('connType').textContent = 'broadcastchannel --status=error ❌'; 
}

function sendMessage(msg){ if(usingBroadcast && channel) return channel.postMessage(msg); try{ localStorage.setItem('game-100mx', JSON.stringify({msg, ts:Date.now()})); return true; }catch(e){ console.debug('[board] ls fail', e); return false; } }

function addStorageListener(fn){ window.addEventListener('storage', (ev)=>{ if(!ev.key || ev.key !== 'game-100mx') return; try{ const d = JSON.parse(ev.newValue); fn(d.msg); }catch(e){} }); }

if(usingBroadcast && channel){ 
    channel.onmessage = (ev)=>{ 
        console.log('[board] Mensaje recibido via BroadcastChannel:', ev.data); 
        handleIncoming(ev.data); 
    }; 
    console.log('[board] Escuchando mensajes via BroadcastChannel');
} else { 
    addStorageListener((m)=>{ 
        console.log('[board] Mensaje recibido via localStorage:', m); 
        handleIncoming(m); 
    }); 
    document.getElementById('connType').textContent = 'localstorage --fallback=true ⚠️'; 
    console.log('[board] Usando localStorage como fallback');
}

const answersEl = document.getElementById('answers'); const stateEl = document.getElementById('state'); const questionEl = document.getElementById('question'); 
const team1ScoreEl = document.getElementById('team1Score'); const team1NameEl = document.getElementById('team1Name');
const team2ScoreEl = document.getElementById('team2Score'); const team2NameEl = document.getElementById('team2Name');
const team1CardEl = document.getElementById('team1Card');
const team2CardEl = document.getElementById('team2Card');
let answers = [];
let currentRound = {points:0, teams:[], accumulatedPoints:0};
let roundReadySent = false;
let currentRoundNumber = 1; // Track current round number
let teamScores = {}; // { 'Familia A': 0, 'Familia B': 0 }
let strikeCount = 0; // Counter for X's
let activeTeam = null; // highlighted team answering

// Sound effects using Web Audio API (terminal-style sounds)
const audioContext = new (window.AudioContext || window.webkitAudioContext)();

function playErrorSound() {
    // Error sound: low harsh beep (compile error)
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    oscillator.frequency.value = 200; // Low frequency
    oscillator.type = 'square'; // Harsh sound
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.3);
    console.log('🔊 Error sound');
}

function playSuccessSound() {
    // Success sound: rising beeps (build success)
    const times = [0, 0.08, 0.16];
    const freqs = [600, 800, 1000];
    times.forEach((time, idx) => {
        const osc = audioContext.createOscillator();
        const gain = audioContext.createGain();
        osc.connect(gain);
        gain.connect(audioContext.destination);
        osc.frequency.value = freqs[idx];
        osc.type = 'sine';
        gain.gain.setValueAtTime(0.2, audioContext.currentTime + time);
        gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + time + 0.15);
        osc.start(audioContext.currentTime + time);
        osc.stop(audioContext.currentTime + time + 0.15);
    });
    console.log('🔊 Success sound');
}

function playBootSound() {
    // Boot sound: terminal startup (low to high sweep)
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    oscillator.type = 'sawtooth';
    oscillator.frequency.setValueAtTime(100, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.5);
    gainNode.gain.setValueAtTime(0.15, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
    console.log('🔊 Boot sound');
}

function renderStrikes(){
    const strikesDisplay = document.getElementById('strikesDisplay');
    if(!strikesDisplay) return;
    
    strikesDisplay.innerHTML = '';
    for(let i = 0; i < strikeCount; i++){
        const xMark = document.createElement('div');
        xMark.style.cssText = `
            font-size: 60px;
            color: #ef4444;
            font-weight: bold;
            text-shadow: 0 0 20px rgba(239, 68, 68, 0.8),
                         0 0 40px rgba(239, 68, 68, 0.6),
                         0 4px 8px rgba(0,0,0,0.5);
            animation: strikeAppear 0.3s ease-out;
        `;
        xMark.textContent = '❌';
        strikesDisplay.appendChild(xMark);
    }
}

function render(){
    answersEl.innerHTML = '';
    // sort by count descending
    const sorted = (answers || []).slice().sort((a,b)=> (Number(b.count||0) - Number(a.count||0)));
    const slots = Math.max(sorted.length,6);
    const rows = Math.ceil(slots/2);
    // placement array that will hold items in row-major order, but we fill it column-major
    const placement = new Array(slots).fill(null);
    // fill left column top-to-bottom with the first 'rows' items, then right column top-to-bottom
    for (let k = 0; k < slots; k++){
        const item = sorted[k] || {text:'',count:0,revealed:false};
        if (k < rows){
            // left column, row k -> index = row*2 + 0
            const idx = k * 2;
            placement[idx] = item;
        } else {
            // right column: for remaining items, place at index = (k-rows)*2 + 1
            const idx = (k - rows) * 2 + 1;
            placement[idx] = item;
        }
    }

    let total = 0;
    // now placement is in row-major order for a 2-column grid
    for (let i = 0; i < slots; i++){
        const a = placement[i] || {text:'',count:0,revealed:false};
        const cell = document.createElement('div');
        cell.className = 'cell' + (a.revealed ? ' revealed' : ' locked');
        
        // Agregar data-answer-index para efectos de partículas
        const originalIndex = answers.findIndex(answer => answer.text === a.text && answer.count === a.count);
        if (originalIndex !== -1) {
            cell.setAttribute('data-answer-index', originalIndex);
        }
        
        cell.innerHTML = `<div class="text">${a.revealed ? escapeHtml(a.text) : '•••••••••••••••'}</div><div class="count">${a.revealed ? a.count : '---'}</div>`;
        answersEl.appendChild(cell);
        if(a.revealed) total += Number(a.count||0);
    }

    renderTeamScores();
}

function renderTeamScores(){
    // Update large side scores only
    const teamNames = Object.keys(teamScores);
    if(teamNames.length >= 1){
        const team1 = teamNames[0];
        if(team1NameEl) team1NameEl.textContent = team1;
        if(team1ScoreEl) team1ScoreEl.textContent = String(teamScores[team1]||0).padStart(3,'0');
    } else {
        if(team1NameEl) team1NameEl.textContent = 'Equipo 1';
        if(team1ScoreEl) team1ScoreEl.textContent = '000';
    }
    
    if(teamNames.length >= 2){
        const team2 = teamNames[1];
        if(team2NameEl) team2NameEl.textContent = team2;
        if(team2ScoreEl) team2ScoreEl.textContent = String(teamScores[team2]||0).padStart(3,'0');
    } else {
        if(team2NameEl) team2NameEl.textContent = 'Equipo 2';
        if(team2ScoreEl) team2ScoreEl.textContent = '000';
    }
}

function appendLog(txt){}

function handleIncoming(msg){ 
    console.log('[board] =====> Mensaje recibido:', msg);
    
    // Actualizar indicador de conexión activa
    const connTypeEl = document.getElementById('connType');
    if(connTypeEl) {
        const currentText = connTypeEl.textContent;
        if(currentText.includes('✅')) {
            connTypeEl.textContent = currentText.replace('✅', '🟢');
            setTimeout(() => {
                connTypeEl.textContent = currentText;
            }, 500);
        }
    }
    
    if(!msg || !msg.type) {
        console.log('[board] Mensaje inválido (sin type)');
        return;
    }
    // ACK: responde a cualquier mensaje que lo solicite
    try{
        if(msg._ack && msg._id && msg.type !== 'ack'){
            sendMessage({type:'ack', id: msg._id});
        }
    }catch(e){}
    console.log('[board] Procesando tipo:', msg.type);
    
    if(msg.type === 'init'){
        answers = (msg.payload.answers||[]).map(a=>({...a}));
        stateEl.textContent = msg.payload.state || 'Listo';
        questionEl.textContent = msg.payload.question ? msg.payload.question : '(sin texto)';
        render();
        // a new init usually means a new round lifecycle; don't auto-emit round_ready here
    } else if(msg.type === 'reveal'){
        const idx = msg.payload.index;
        if(!Number.isFinite(idx) || !answers[idx]){ return; }
        // Defensive: ignore duplicate reveals of the same index
        if(answers[idx].revealed){ return; }
        answers[idx].revealed = true;
        
        // 🖥️ Mostrar en terminal
        showTerminalMessage(`reveal --answer=${idx+1} --text="${answers[idx].text}" ✓`);
        
        playSuccessSound(); // ✅ Success sound
        render(); // render triggers the reveal animation on inserted .cell.revealed
        
        // 🌟 Efectos de partículas para respuestas altas
        setTimeout(() => {
            const revealedCell = document.querySelector(`[data-answer-index="${idx}"]`);
            if (revealedCell && answers[idx].points >= 10) {
                createHighScoreParticles(revealedCell, answers[idx].points);
            }
        }, 800); // Delay para que coincida con la animación de reveal
        
        // 🌟 Verificar si es ronda perfecta después de un breve delay (solo si no es la última ronda)
        // En la ronda 3, checkGameEnd se encarga de verificar ronda perfecta primero
        if (currentRoundNumber < 3) {
            setTimeout(() => {
                checkPerfectRound();
            }, 500);
        }
    } else if(msg.type === 'state'){
        stateEl.textContent = msg.payload.state;
    } else if(msg.type === 'update_strikes'){
        // Update strike count
        const prevCount = strikeCount;
        strikeCount = Number((msg.payload && msg.payload.count) || 0);
        if(strikeCount > prevCount) {
            playErrorSound(); // ❌ Compile error sound
            showTerminalMessage(`strike --count=${strikeCount}/3 --penalty=true ❌`);
            
            // 💥 Animación shake para nueva X
            const strikesContainer = document.querySelector('.strikes');
            const gameBoard = document.querySelector('.game-board');
            
            if (strikesContainer) {
                strikesContainer.classList.add('shake-animation');
                setTimeout(() => {
                    strikesContainer.classList.remove('shake-animation');
                }, 800);
            }
            
            if (gameBoard) {
                gameBoard.classList.add('strike-flash');
                setTimeout(() => {
                    gameBoard.classList.remove('strike-flash');
                }, 500);
            }
        }
        renderStrikes();
    } else if(msg.type === 'fast_money_curtain'){
        // 🎭 Mostrar animación de telón para dinero rápido
        if(msg.payload && msg.payload.action === 'start') {
            showCurtainAnimation('¡PREPARANDO DINERO RÁPIDO!');
        }
    } else if(msg.type === 'switch_fast_money'){
        // Cambiar a modo Dinero Rápido
        if(msg.payload && msg.payload.mode === 'start') {
            showFastMoneyMode();
        } else if(msg.payload && msg.payload.mode === 'exit') {
            hideFastMoneyMode();
        }
    } else if(msg.type === 'fast_money_question'){
        console.log('📨 Tablero recibió fast_money_question:', msg);
        // Manejar preguntas de Fast Money
        if(msg.payload) {
            updateFastMoneyQuestion(msg.payload.question, msg.payload.index);
        } else {
            console.error('❌ Mensaje fast_money_question sin payload');
        }
    } else if(msg.type === 'fast_money_reveal'){
        // Manejar revelado de respuestas
        if(msg.payload) {
            const hidePoints = msg.payload.hidePoints || false;
            const participant = msg.payload.participant || 1;
            const questionIndex = msg.payload.questionIndex || 0;
            revealFastMoneyAnswer(msg.payload.answerIndex, msg.payload.answer, msg.payload.points, msg.payload.totalScore, hidePoints, participant, questionIndex);
        }
    } else if(msg.type === 'fast_money_reveal_total'){
        // 💰 Revelar puntos totales al final
        if(msg.payload) {
            revealFastMoneyTotal(msg.payload.totalScore, msg.payload.target, msg.payload.success);
        }
    } else if(msg.type === 'fast_money_reveal_step'){
        // 🎯 Revelar una respuesta específica paso a paso
        if(msg.payload) {
            revealAnswerStep(msg.payload.participant, msg.payload.questionIndex, msg.payload.answer, msg.payload.points, msg.payload.stepNumber, msg.payload.totalSteps);
        }
    } else if(msg.type === 'fast_money_curtain'){
        // 🎭 Mostrar animación de telón
        showCurtainAnimation();
    } else if(msg.type === 'hide_participant_points'){
        // 🔒 Ocultar puntos del participante anterior
        if(msg.payload && msg.payload.participant) {
            hideParticipantPoints(msg.payload.participant);
        }
    } else if(msg.type === 'fast_money_target_update'){
        // 🎯 Actualizar meta del dinero rápido
        if(msg.payload && msg.payload.target) {
            updateFastMoneyTargetDisplay(msg.payload.target);
        }
    } else if(msg.type === 'update_participant_scores'){
        // 📊 Actualizar scores individuales de participantes
        if(msg.payload) {
            updateIndividualParticipantScores(msg.payload.participant1Score, msg.payload.participant2Score);
        }
    } else if(msg.type === 'fast_money_victory_animation'){
        // 🎉 Mostrar animación de victoria
        if(msg.payload) {
            showVictoryAnimation(msg.payload.totalScore, msg.payload.target, msg.payload.difference);
        }
    } else if(msg.type === 'fast_money_defeat_animation'){
        // 😔 Mostrar animación de derrota
        if(msg.payload) {
            showDefeatAnimation(msg.payload.totalScore, msg.payload.target, msg.payload.difference);
        }
    } else if(msg.type === 'fast_money_reset'){
        // Manejar reset de Fast Money
        resetFastMoneyBoard();
    } else if(msg.type === 'fast_money_finish'){
        // Manejar finalización de Fast Money
        if(msg.payload) {
            finishFastMoneyBoard(msg.payload.success, msg.payload.finalScore);
            
            // Mostrar respuestas seleccionadas si se solicitó
            if(msg.payload.showResults && msg.payload.sessionData) {
                setTimeout(() => {
                    showFastMoneyResultsOnBoard(msg.payload.sessionData);
                }, 2000);
            }
        }
    } else if(msg.type === 'round_points'){
        // controller started a round; store points & teams and update display
        playBootSound(); // 🚀 Terminal boot sound for round start
        currentRound.points = Number((msg.payload && msg.payload.points) || 0);
        currentRound.teams = (msg.payload && msg.payload.teams) || [];
        currentRound.accumulatedPoints = 0; // reset accumulated points for new round
        roundReadySent = false;
        document.getElementById('roundPointsDisplay').textContent = '0'; // start at 0
        
        // Update round number display
        const roundNum = Number((msg.payload && msg.payload.roundNumber) || 1);
        currentRoundNumber = roundNum; // Actualizar número de ronda global
        const roundNumEl = document.getElementById('roundNumberDisplay');
        if(roundNumEl) roundNumEl.textContent = `Ronda: ${roundNum}`;
        
        // 🎭 MOSTRAR TELÓN TEATRAL para cambio de ronda
        const keepScores = !!(msg.payload && msg.payload.keepScores);
        const multiplier = Number((msg.payload && msg.payload.multiplier) || 1);
        const multiplierText = multiplier > 1 ? ` — ${multiplier === 2 ? 'DOBLE' : 'TRIPLE'} PUNTOS` : '';
        
        console.log(`[board] Telón - keepScores: ${keepScores}, roundNum: ${roundNum}`);
        
        if(!keepScores) {
            // Es "Nueva partida" - SIEMPRE mostrar telón y mensaje
            console.log('[board] Mostrando telón para NUEVA PARTIDA');
            showTerminalMessage('game --new-match --initializing... 🎮');
            showCurtainTransition('NUEVA PARTIDA', 'Cargando nueva partida...');
        } else if(keepScores && roundNum > 1) {
            // Es "Siguiente ronda" - mostrar info de ronda
            console.log(`[board] Mostrando telón para RONDA ${roundNum}`);
            showTerminalMessage(`round --next=${roundNum} --multiplier=${multiplier}x 🎯`);
            showCurtainTransition(roundNum, `¡Que comience el desafío!${multiplierText}`);
        } else if(keepScores && roundNum === 1) {
            // Es "Iniciar ronda" (primera ronda manteniendo equipos)
            console.log(`[board] Mostrando telón para inicio de RONDA ${roundNum}`);
            showTerminalMessage(`round --start=1 --teams-ready 🚀`);
            showCurtainTransition(roundNum, `¡Que comience el desafío!${multiplierText}`);
        }
        
        // Update multiplier display (reutilizar la variable multiplier ya declarada)
        const multiplierEl = document.getElementById('multiplierDisplay');
        if(multiplierEl){
            if(multiplier > 1){
                multiplierEl.textContent = `🔥 x${multiplier} ${multiplier === 2 ? 'DOBLE' : 'TRIPLE'}`;
                multiplierEl.style.display = 'inline';
            } else {
                multiplierEl.textContent = '';
                multiplierEl.style.display = 'none';
            }
        }
        // clear previous round winner
        const rw = document.getElementById('roundWinner'); if(rw) rw.textContent = '';
        // reset strikes
        strikeCount = 0;
        renderStrikes();
    // Replace teamScores depending on keepScores flag
    const keep = !!(msg.payload && msg.payload.keepScores);
    if(keep){
        // preserve prior left/right scores by position
        const newScores = {};
        (currentRound.teams || []).forEach((t, idx)=>{
            const keys = Object.keys(teamScores);
            const prior = (idx === 0) ? (keys[0] || t) : (keys[1] || t);
            newScores[t] = Number(teamScores[prior] || 0);
        });
        teamScores = newScores;
    } else {
        // fresh game: reset both teams to 0
        teamScores = {};
        (currentRound.teams || []).forEach(t=>{ teamScores[t] = 0; });
    }
        persistTeamScores();
        renderTeamScores();
    } else if(msg.type === 'multiplier'){
        // Live update of multiplier banner during an active round
        const multiplier = Number((msg.payload && msg.payload.multiplier) || 1);
        const multiplierEl = document.getElementById('multiplierDisplay');
        if(multiplierEl){
            if(multiplier > 1){
                multiplierEl.textContent = `🔥 x${multiplier} ${multiplier === 2 ? 'DOBLE' : 'TRIPLE'}`;
                multiplierEl.style.display = 'inline';
            } else {
                multiplierEl.textContent = '';
                multiplierEl.style.display = 'none';
            }
        }
        
        // Update the displayed points with new multiplier
        const basePts = Number(currentRound.accumulatedPoints || 0);
        const finalPts = basePts * multiplier;
        const rpd = document.getElementById('roundPointsDisplay');
        if(rpd) rpd.textContent = String(finalPts);
    } else if(msg.type === 'update_round_total'){
        // Update the live round points (WITH multiplier applied)
        const basePts = Number((msg.payload && msg.payload.points) || 0);
        const multiplier = Number((msg.payload && msg.payload.multiplier) || 1);
        const finalPts = basePts * multiplier;
        currentRound.accumulatedPoints = basePts; // Store base points
        const rpd = document.getElementById('roundPointsDisplay');
        if(rpd) rpd.textContent = String(finalPts); // Display multiplied points
    } else if(msg.type === 'team_names'){
        // Live rename of teams preserving scores by position
        const teams = (msg.payload && msg.payload.teams) || [];
        if(Array.isArray(teams) && teams.length === 2){
            const keys = Object.keys(teamScores);
            const leftOld = keys[0];
            const rightOld = keys[1];
            const newScores = {};
            newScores[teams[0]] = Number(teamScores[leftOld] || 0);
            newScores[teams[1]] = Number(teamScores[rightOld] || 0);
            teamScores = newScores;
            persistTeamScores();
            renderTeamScores();
            // keep highlight with the same side if active
            if(activeTeam === leftOld) activeTeam = teams[0];
            if(activeTeam === rightOld) activeTeam = teams[1];
            if(team1CardEl) team1CardEl.classList.toggle('active', !!activeTeam && activeTeam === teams[0]);
            if(team2CardEl) team2CardEl.classList.toggle('active', !!activeTeam && activeTeam === teams[1]);
        }
        // Update accumulated round points display
        const accumulatedPts = Number((msg.payload && msg.payload.points) || 0);
        currentRound.accumulatedPoints = accumulatedPts;
        document.getElementById('roundPointsDisplay').textContent = String(accumulatedPts);
    } else if(msg.type === 'steal'){
        // Show a visual banner for steal/robo de puntos
        const toTeam = msg.payload && msg.payload.toTeam ? String(msg.payload.toTeam) : '';
        const pts = msg.payload && Number(msg.payload.points) ? Number(msg.payload.points) : 0;
        showStealBanner(toTeam, pts);
    } else if(msg.type === 'active_team'){
        // Highlight which team is currently answering
        activeTeam = (msg.payload && msg.payload.team) ? String(msg.payload.team) : null;
        
        // Hide legacy banner and use card highlight instead
        const banner = document.getElementById('activeTurnBanner');
        if(banner) banner.style.display = 'none';

        // Toggle card highlight with dramatic transitions
        const t1Name = team1NameEl ? team1NameEl.textContent : '';
        const t2Name = team2NameEl ? team2NameEl.textContent : '';
        
        // Remove previous animations
        if(team1CardEl) team1CardEl.classList.remove('team-transition', 'team-pulse');
        if(team2CardEl) team2CardEl.classList.remove('team-transition', 'team-pulse');
        
        let activeCard = null;
        if(team1CardEl){ 
            team1CardEl.classList.toggle('active', !!activeTeam && activeTeam === t1Name);
            if(!!activeTeam && activeTeam === t1Name) activeCard = team1CardEl;
        }
        if(team2CardEl){ 
            team2CardEl.classList.toggle('active', !!activeTeam && activeTeam === t2Name);
            if(!!activeTeam && activeTeam === t2Name) activeCard = team2CardEl;
        }
        
        // 🎭 Dramatic team transition effect
        if(activeCard && activeTeam) {
            activeCard.classList.add('team-transition');
            setTimeout(() => {
                activeCard.classList.add('team-pulse');
            }, 1000);
            
            // Clean up animation classes after completion
            setTimeout(() => {
                activeCard.classList.remove('team-transition', 'team-pulse');
            }, 2100);
        }

        renderTeamScores();
    } else if(msg.type === 'assign_points'){
        const p = (msg.payload && Number(msg.payload.points)) || 0;
        const team = (msg.payload && msg.payload.team) || '';
        const roundNum = (msg.payload && msg.payload.roundNumber) || 0;
        
        // Update team scores
        if(team){
            if(!(team in teamScores)) teamScores[team] = 0;
            teamScores[team] = Number(teamScores[team] || 0) + Number(p || 0);
            
            // 🖥️ Mostrar en terminal
            showTerminalMessage(`points --team="${team}" --add=${p} --total=${teamScores[team]} 💰`);
            
            persistTeamScores();
            renderTeamScores();
            
            // 🏆 Verificar si el juego ha terminado (Ronda 3)
            if(roundNum >= 3) {
                checkGameEnd(roundNum);
            }
        }
    } else if(msg.type === 'test_connection'){
        // Test de conexión desde controller
        console.log('[board] ✅ Test de conexión recibido exitosamente:', msg);
        const connTypeEl = document.getElementById('connType');
        if(connTypeEl) {
            const originalText = connTypeEl.textContent;
            connTypeEl.textContent = 'ping controller --result=success ✓';
            connTypeEl.style.color = '#00ff00';
            setTimeout(() => {
                connTypeEl.textContent = originalText;
                connTypeEl.style.color = '#00ff00';
            }, 3000);
        }
        // Enviar respuesta de vuelta al controller
        sendMessage({
            type: 'test_response', 
            timestamp: Date.now(),
            payload: { message: 'Conexión confirmada desde board' }
        });
    } else if(msg.type === 'reset_all'){
        // Complete reset of everything
        console.log('[board] ===== RESET ALL INICIADO =====');
        console.log('[board] strikeCount ANTES:', strikeCount);
        
        answers = [];
        teamScores = {};
        currentRound = {points:0, teams:[], accumulatedPoints:0};
        strikeCount = 0;
        roundReadySent = false;
        activeTeam = null;
        
        console.log('[board] strikeCount DESPUÉS:', strikeCount);
        
        // Clear localStorage
        localStorage.removeItem('game-team-scores');
        
        // Update all displays
        questionEl.textContent = '(esperando)';
        stateEl.textContent = 'Listo';
        document.getElementById('roundPointsDisplay').textContent = '0';
        const roundNumEl = document.getElementById('roundNumberDisplay');
        if(roundNumEl) roundNumEl.textContent = 'Ronda: 1';
        const multiplierEl = document.getElementById('multiplierDisplay');
        if(multiplierEl) { multiplierEl.textContent = ''; multiplierEl.style.display = 'none'; }
        // Reset team scores
        if(team1NameEl) team1NameEl.textContent = 'Equipo 1';
        if(team1ScoreEl) team1ScoreEl.textContent = '000';
        if(team2NameEl) team2NameEl.textContent = 'Equipo 2';
        if(team2ScoreEl) team2ScoreEl.textContent = '000';
        // Hide active turn banner
    const banner = document.getElementById('activeTurnBanner');
    if(banner) banner.style.display = 'none';
    // Remove card highlights
    if(team1CardEl) team1CardEl.classList.remove('active');
    if(team2CardEl) team2CardEl.classList.remove('active');
        
        // Force clear team scores display
        const teamScoresEl = document.getElementById('teamScores');
        console.log('[board] teamScoresEl existe?', !!teamScoresEl);
        if(teamScoresEl) {
            teamScoresEl.innerHTML = '';
            console.log('[board] teamScores limpiado');
        }
        
        // Force clear strikes display - MÉTODO 1: innerHTML
        const strikesDisplayEl = document.getElementById('strikesDisplay');
        console.log('[board] strikesDisplayEl existe?', !!strikesDisplayEl);
        if(strikesDisplayEl) {
            console.log('[board] Contenido ANTES de limpiar:', strikesDisplayEl.innerHTML);
            strikesDisplayEl.innerHTML = '';
            console.log('[board] Contenido DESPUÉS de limpiar:', strikesDisplayEl.innerHTML);
            
            // MÉTODO 2: Remover todos los hijos uno por uno (por si innerHTML no funciona)
            while(strikesDisplayEl.firstChild) {
                strikesDisplayEl.removeChild(strikesDisplayEl.firstChild);
            }
            console.log('[board] Hijos removidos manualmente');
        }
        
        // Reset and render
        console.log('[board] Llamando renderStrikes()');
        renderStrikes();
        renderTeamScores();
        render();
        // hide steal overlay if visible
        const so = document.getElementById('stealOverlay'); if(so) so.style.display = 'none';
        console.log('[board] ===== RESET ALL COMPLETADO =====');
    }
}

// persist teamScores to localStorage to keep across tabs
function persistTeamScores(){
    try{ localStorage.setItem('game-team-scores', JSON.stringify(teamScores)); }catch(e){ console.debug('persistTeamScores fail', e); }
}

// load persisted team scores at startup
try{ const stored = localStorage.getItem('game-team-scores'); if(stored){ teamScores = JSON.parse(stored) || {}; } }catch(e){}

// Note: round completion and assignment is handled by the controller; the board is visual-only.

// Ask the controller for current state
function requestInit(){ sendMessage({type:'request_init'}); }
requestInit(); setTimeout(requestInit, 400);
let attempts = 0; const poll = setInterval(()=>{ attempts++; if(answers.length>0 || attempts>12){ clearInterval(poll); return; } requestInit(); }, 2000);

// convenience: reveal next with keyboard R
document.addEventListener('keydown', (e)=>{ if(e.key && e.key.toLowerCase() === 'r'){ const idx = answers.findIndex(a=>!a.revealed); if(idx !== -1){ sendMessage({type:'reveal', payload:{index:idx}}); } } });

// small helper to avoid XSS in injected text
function escapeHtml(s){ if(!s) return ''; return s.replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

// Terminal message system
let terminalTimeout = null;
function showTerminalMessage(message) {
    const connTypeEl = document.getElementById('connType');
    if(!connTypeEl) return;
    
    const originalText = connTypeEl.textContent;
    connTypeEl.textContent = message;
    connTypeEl.style.color = '#ffff00'; // Yellow for activity
    
    if(terminalTimeout) clearTimeout(terminalTimeout);
    terminalTimeout = setTimeout(() => {
        connTypeEl.textContent = originalText;
        connTypeEl.style.color = '#00ff00'; // Back to green
    }, 2500);
}

// Steal overlay helper
let stealTimeout = null;
function showStealBanner(team, points){
    const overlay = document.getElementById('stealOverlay');
    const sub = document.getElementById('stealOverlaySub');
    if(!overlay) return;
    if(sub){
        const t = team ? `→ ${escapeHtml(team)}` : '';
        const p = Number(points||0) > 0 ? `+${points}` : '';
        sub.textContent = `${t} ${p}`.trim();
    }
    overlay.style.display = 'flex';
    overlay.style.opacity = '1';
    overlay.style.transition = 'opacity .4s ease';
    if(stealTimeout) clearTimeout(stealTimeout);
    stealTimeout = setTimeout(()=>{
        overlay.style.opacity = '0';
        setTimeout(()=>{ overlay.style.display = 'none'; }, 400);
    }, 2200);
}

// ===== TEATRO TELON FUNCTIONS =====
function playCurtainSound() {
    // Sonido de telón: Un sonido suave y dramático como de teatro
    try {
        const oscillator1 = audioContext.createOscillator();
        const oscillator2 = audioContext.createOscillator();
        const gainNode1 = audioContext.createGain();
        const gainNode2 = audioContext.createGain();
        
        oscillator1.connect(gainNode1);
        oscillator2.connect(gainNode2);
        gainNode1.connect(audioContext.destination);
        gainNode2.connect(audioContext.destination);
        
        // Acordes dramáticos
        oscillator1.frequency.value = 220; // A3
        oscillator2.frequency.value = 330; // E4
        oscillator1.type = 'sine';
        oscillator2.type = 'sine';
        
        gainNode1.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode1.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 2.0);
        gainNode2.gain.setValueAtTime(0.08, audioContext.currentTime);
        gainNode2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 2.0);
        
        oscillator1.start(audioContext.currentTime);
        oscillator2.start(audioContext.currentTime);
        oscillator1.stop(audioContext.currentTime + 2.0);
        oscillator2.stop(audioContext.currentTime + 2.0);
        
        console.log('🎭 Sonido de telón teatral');
    } catch(e) {
        console.log('Error reproduciendo sonido de telón:', e);
    }
}

function showCurtainTransition(roundNumber, subtitle) {
    const curtainOverlay = document.getElementById('curtainOverlay');
    const roundTitle = document.getElementById('roundTransitionTitle');
    const roundSubtitle = document.getElementById('roundTransitionSubtitle');
    const message = document.getElementById('roundTransitionMessage');
    
    if (!curtainOverlay) return;
    
    // Configurar el mensaje - detectar si es nueva partida o ronda
    if (roundTitle) {
        if (roundNumber === 'NUEVA PARTIDA') {
            roundTitle.textContent = 'NUEVA PARTIDA';
        } else {
            roundTitle.textContent = `RONDA ${roundNumber}`;
        }
    }
    if (roundSubtitle) roundSubtitle.textContent = subtitle || '¡Preparando nueva ronda!';
    
    // Mostrar overlay y iniciar animación de cierre
    curtainOverlay.style.display = 'flex';
    curtainOverlay.className = 'curtain-overlay curtain-closing';
    
    // Reproducir sonido teatral
    playCurtainSound();
    
    // Mostrar mensaje después de que se cierre el telón
    setTimeout(() => {
        if (message) message.classList.add('message-visible');
    }, 1800);
    
    // Abrir telón después de mostrar el mensaje
    setTimeout(() => {
        curtainOverlay.className = 'curtain-overlay curtain-opening';
    }, 4000);
    
    // Ocultar completamente después de la animación
    setTimeout(() => {
        curtainOverlay.style.display = 'none';
        curtainOverlay.className = 'curtain-overlay';
        if (message) message.classList.remove('message-visible');
    }, 5200);
    
    console.log(`🎭 Transición de telón: ${roundNumber}`);
}

// 🎭 Función específica para animación del telón (sin mensaje de ronda)
function showCurtainAnimation(customMessage) {
    const curtainOverlay = document.getElementById('curtainOverlay');
    const roundTitle = document.getElementById('roundTransitionTitle');
    const roundSubtitle = document.getElementById('roundTransitionSubtitle');
    const message = document.getElementById('roundTransitionMessage');
    
    if (!curtainOverlay) return;
    
    // Configurar el mensaje personalizado
    if (roundTitle) roundTitle.textContent = customMessage || '¡PREPARANDO MODO ESPECIAL!';
    if (roundSubtitle) roundSubtitle.textContent = '';
    
    // Mostrar overlay y iniciar animación de cierre
    curtainOverlay.style.display = 'flex';
    curtainOverlay.className = 'curtain-overlay curtain-closing';
    
    // Reproducir sonido teatral
    playCurtainSound();
    
    // Mostrar mensaje después de que se cierre el telón
    setTimeout(() => {
        if (message) message.classList.add('message-visible');
    }, 1800);
    
    // Abrir telón después de mostrar el mensaje
    setTimeout(() => {
        curtainOverlay.className = 'curtain-overlay curtain-opening';
    }, 3500); // Tiempo más corto que la transición de ronda
    
    // Ocultar completamente después de la animación
    setTimeout(() => {
        curtainOverlay.style.display = 'none';
        curtainOverlay.className = 'curtain-overlay';
        if (message) message.classList.remove('message-visible');
    }, 4700); // Tiempo total más corto
    
    console.log(`🎭 Animación de telón: ${customMessage}`);
}

// Función showRoundFinishCurtain eliminada - no se usa

// ===== PERFECT ROUND FUNCTIONS =====
function playPerfectSound() {
    try {
        // Sonido de ronda perfecta: Acordes ascendentes épicos
        const notes = [261.63, 329.63, 392.00, 523.25, 659.25, 783.99]; // C4, E4, G4, C5, E5, G5
        notes.forEach((freq, index) => {
            setTimeout(() => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.frequency.value = freq;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.6);
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.6);
            }, index * 150);
        });
        console.log('🎵 Perfect round sound!');
    } catch(e) {
        console.log('Error reproduciendo sonido de ronda perfecta:', e);
    }
}

function createPerfectParticles() {
    const container = document.getElementById('perfectParticles');
    if (!container) return;
    
    // Crear 30 partículas doradas
    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'perfect-particle';
        
        const randomX = (Math.random() - 0.5) * 600;
        const randomY = (Math.random() - 0.5) * 400;
        particle.style.setProperty('--random-x', randomX + 'px');
        particle.style.setProperty('--random-y', randomY + 'px');
        particle.style.left = '50%';
        particle.style.top = '50%';
        particle.style.animationDelay = (Math.random() * 2) + 's';
        
        container.appendChild(particle);
        
        // Remover después de la animación
        setTimeout(() => {
            if (particle.parentNode) {
                particle.parentNode.removeChild(particle);
            }
        }, 5000);
    }
}

function createPerfectRings() {
    const container = document.getElementById('perfectRings');
    if (!container) return;
    
    // Crear 3 anillos expandiendo
    for (let i = 0; i < 3; i++) {
        setTimeout(() => {
            const ring = document.createElement('div');
            ring.className = 'perfect-ring';
            ring.style.marginLeft = '-25px';
            ring.style.marginTop = '-25px';
            container.appendChild(ring);
            
            // Remover después de la animación
            setTimeout(() => {
                if (ring.parentNode) {
                    ring.parentNode.removeChild(ring);
                }
            }, 2000);
        }, i * 600);
    }
}

function showPerfectRound(teamName) {
    const overlay = document.getElementById('perfectRoundOverlay');
    const teamElement = document.getElementById('perfectTeam');
    
    if (!overlay) return;
    
    // Configurar datos del equipo
    if (teamElement) teamElement.textContent = teamName;
    
    // Mostrar overlay
    overlay.style.display = 'flex';
    
    // Reproducir sonido épico
    playPerfectSound();
    
    // Mostrar mensaje en terminal
    showTerminalMessage(`perfect-round --team="${teamName}" --bonus=50 🌟`);
    
    // Iniciar efectos especiales
    setTimeout(() => createPerfectParticles(), 300);
    setTimeout(() => createPerfectRings(), 500);
    setTimeout(() => createPerfectParticles(), 2000);
    
    console.log(`🌟 ¡Ronda perfecta de ${teamName}!`);
    
    // Ocultar después de 4 segundos
    setTimeout(() => {
        overlay.style.display = 'none';
        // Limpiar contenedores
        const particlesContainer = document.getElementById('perfectParticles');
        const ringsContainer = document.getElementById('perfectRings');
        if (particlesContainer) particlesContainer.innerHTML = '';
        if (ringsContainer) ringsContainer.innerHTML = '';
    }, 4000);
    
    return true; // Indica que se mostró la animación
}

function checkPerfectRound() {
    // Verificar si todas las respuestas están reveladas
    const allRevealed = answers.every(answer => answer.revealed);
    
    if (allRevealed && answers.length > 0 && activeTeam) {
        // ¡Ronda perfecta! El equipo activo respondió todo correctamente
        setTimeout(() => {
            showPerfectRound(activeTeam);
        }, 1000); // Pequeño delay para que se vea la última respuesta
        
        return true;
    }
    
    return false;
}

// 🌟 Crear efectos de partículas para respuestas altas
function createHighScoreParticles(element, score) {
    const rect = element.getBoundingClientRect();
    const container = document.createElement('div');
    container.className = 'high-score-particles';
    container.style.left = rect.left + 'px';
    container.style.top = rect.top + 'px';
    container.style.width = rect.width + 'px';
    container.style.height = rect.height + 'px';
    
    document.body.appendChild(container);
    
    // Determinar cantidad de partículas según el score
    let particleCount = 0;
    if (score >= 30) particleCount = 15; // Top respuestas
    else if (score >= 20) particleCount = 10; // Respuestas altas
    else if (score >= 10) particleCount = 5; // Respuestas medias
    
    // Crear partículas
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Color según score
        if (score >= 30) particle.classList.add('gold');
        else if (score >= 20) particle.classList.add('orange');
        else particle.classList.add('yellow');
        
        // Posición aleatoria
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        
        // Delay aleatorio
        particle.style.animationDelay = Math.random() * 0.5 + 's';
        
        container.appendChild(particle);
    }
    
    // Limpiar después de la animación
    setTimeout(() => {
        if (document.body.contains(container)) {
            document.body.removeChild(container);
        }
    }, 2500);
}

// ===== FAST MONEY MODE FUNCTIONS =====

function showFastMoneyMode() {
    const overlay = document.getElementById('fastMoneyOverlay');
    if(!overlay) return;
    
    // Show overlay
    overlay.style.display = 'flex';
    
    // Initialize Fast Money data
    initializeFastMoney();
    
    // Terminal message
    showTerminalMessage('fast-money --mode=active --status=ready 💰');
    
    // Sound effect
    playFastMoneySound();
}

function hideFastMoneyMode() {
    const overlay = document.getElementById('fastMoneyOverlay');
    if(overlay) {
        overlay.style.display = 'none';
    }
    
    showTerminalMessage('fast-money --mode=exit --status=complete 🚪');
}

function initializeFastMoney() {
    // Reset score
    document.getElementById('fastMoneyScore').textContent = '0';
    
    // Sample questions (you can customize these)
    const fastMoneyQuestions = [
        { question: "Nombra un lenguaje de programación popular", answers: ["JavaScript", "Python", "Java", "C++", "C#"], points: [38, 25, 18, 12, 7] },
        { question: "Nombra un navegador web", answers: ["Chrome", "Firefox", "Safari", "Edge", "Opera"], points: [45, 22, 15, 12, 6] },
        { question: "Nombra una red social", answers: ["Facebook", "Instagram", "Twitter", "TikTok", "LinkedIn"], points: [35, 28, 20, 10, 7] },
        { question: "Nombra un sistema operativo", answers: ["Windows", "macOS", "Linux", "Android", "iOS"], points: [40, 25, 15, 12, 8] },
        { question: "Nombra una empresa de tecnología", answers: ["Google", "Apple", "Microsoft", "Amazon", "Meta"], points: [32, 28, 22, 10, 8] }
    ];
    
    // Set current question
    document.getElementById('fastMoneyQuestion').textContent = fastMoneyQuestions[0].question;
    
    // Reset answer display
    for(let i = 1; i <= 5; i++) {
        const answerEl = document.getElementById(`fastAnswer${i}`);
        if(answerEl) {
            answerEl.classList.remove('revealed');
            const textEl = answerEl.querySelector('.answer-text');
            const pointsEl = answerEl.querySelector('.answer-points');
            if(textEl) textEl.textContent = '---';
            if(pointsEl) pointsEl.textContent = '--';
        }
    }
    
    document.getElementById('fastMoneyMessage').textContent = '¡Listo para comenzar!';
}

function playFastMoneySound() {
    try {
        // Sonido especial para Dinero Rápido
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime); // C5
        oscillator.frequency.setValueAtTime(659.25, audioContext.currentTime + 0.1); // E5
        oscillator.frequency.setValueAtTime(783.99, audioContext.currentTime + 0.2); // G5
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    } catch(error) {
        console.warn('Audio context error:', error);
    }
}

// ===== WINNER CELEBRATION FUNCTIONS =====
function playVictorySound() {
    try {
        // Sonido de victoria épico con múltiples tonos
        const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
        notes.forEach((freq, index) => {
            setTimeout(() => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.frequency.value = freq;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.8);
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.8);
            }, index * 200);
        });
        console.log('🎵 Sonido de victoria!');
    } catch(e) {
        console.log('Error reproduciendo sonido de victoria:', e);
    }
}

function createConfetti() {
    const container = document.getElementById('confettiContainer');
    if (!container) return;
    
    // Crear 50 piezas de confeti
    for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
        confetti.style.animationDelay = Math.random() * 2 + 's';
        container.appendChild(confetti);
        
        // Remover confeti después de la animación
        setTimeout(() => {
            if (confetti.parentNode) {
                confetti.parentNode.removeChild(confetti);
            }
        }, 5000);
    }
}

function createFireworks() {
    const container = document.getElementById('fireworksContainer');
    if (!container) return;
    
    // Crear múltiples explosiones de fuegos artificiales
    for (let i = 0; i < 3; i++) {
        setTimeout(() => {
            const centerX = Math.random() * 100;
            const centerY = Math.random() * 50 + 20;
            
            // Crear partículas para cada explosión
            for (let j = 0; j < 12; j++) {
                const firework = document.createElement('div');
                firework.className = 'firework';
                firework.style.left = centerX + '%';
                firework.style.top = centerY + '%';
                
                const randomX = (Math.random() - 0.5) * 200;
                const randomY = (Math.random() - 0.5) * 200;
                firework.style.setProperty('--random-x', randomX + 'px');
                firework.style.setProperty('--random-y', randomY + 'px');
                
                container.appendChild(firework);
                
                // Remover después de la animación
                setTimeout(() => {
                    if (firework.parentNode) {
                        firework.parentNode.removeChild(firework);
                    }
                }, 2000);
            }
        }, i * 800);
    }
}

function showWinnerCelebration(winnerTeam, winnerScore) {
    const overlay = document.getElementById('winnerOverlay');
    const teamElement = document.getElementById('winnerTeam');
    const scoreElement = document.getElementById('winnerScore');
    
    if (!overlay) return;
    
    // Configurar datos del ganador
    if (teamElement) teamElement.textContent = winnerTeam;
    if (scoreElement) scoreElement.textContent = winnerScore + ' puntos';
    
    // Mostrar overlay
    overlay.style.display = 'flex';
    
    // Reproducir sonido de victoria
    playVictorySound();
    
    // Iniciar efectos especiales
    setTimeout(() => createConfetti(), 500);
    setTimeout(() => createFireworks(), 1000);
    setTimeout(() => createConfetti(), 3000);
    setTimeout(() => createFireworks(), 4000);
    
    console.log(`🏆 ¡Celebración del ganador! ${winnerTeam} con ${winnerScore} puntos`);
    
    // Ocultar después de 8 segundos
    setTimeout(() => {
        overlay.style.display = 'none';
        // Limpiar contenedores
        const confettiContainer = document.getElementById('confettiContainer');
        const fireworksContainer = document.getElementById('fireworksContainer');
        if (confettiContainer) confettiContainer.innerHTML = '';
        if (fireworksContainer) fireworksContainer.innerHTML = '';
    }, 8000);
}

function checkGameEnd(roundNumber) {
    // Si es la Ronda 3 (última ronda), determinar ganador
    if (roundNumber >= 3) {
        const teamNames = Object.keys(teamScores);
        if (teamNames.length >= 2) {
            const team1Score = teamScores[teamNames[0]] || 0;
            const team2Score = teamScores[teamNames[1]] || 0;
            
            let winnerTeam, winnerScore;
            if (team1Score > team2Score) {
                winnerTeam = teamNames[0];
                winnerScore = team1Score;
            } else if (team2Score > team1Score) {
                winnerTeam = teamNames[1];
                winnerScore = team2Score;
            } else {
                // Empate
                winnerTeam = '¡EMPATE!';
                winnerScore = team1Score;
            }
            
            // Verificar si hay ronda perfecta en curso
            const isPerfectRound = checkPerfectRound();
            const celebrationDelay = isPerfectRound ? 6000 : 2000; // 6s si hay ronda perfecta (1s + 4s animación + 1s buffer), 2s normal
            
            // Mostrar celebración después del delay apropiado
            setTimeout(() => {
                showWinnerCelebration(winnerTeam, winnerScore);
                
                // 💰 Notificar al controller para desbloquear Dinero Rápido
                setTimeout(() => {
                    sendMessage({type: 'unlock_fast_money', payload: {winner: winnerTeam, ready: true}});
                }, 3000);
            }, celebrationDelay);
            
            return true; // Juego terminado
        }
    }
    return false; // Juego continúa
}

// Fast Money exit button event listener
document.getElementById('fastMoneyExit')?.addEventListener('click', hideFastMoneyMode);

// ===== FUNCIONES PARA CONTROLADOR DE DINERO RÁPIDO =====

function updateFastMoneyQuestion(questionText, questionIndex) {
    console.log('🎯 updateFastMoneyQuestion ejecutado en tablero');
    console.log('📝 Pregunta:', questionText, 'Índice:', questionIndex);
    
    // Título de pregunta ocultado - solo resetear respuestas
    // const questionElement = document.querySelector('.fast-money-question');
    // if(questionElement) {
    //     questionElement.textContent = questionText;
    // }
    
    // Reset answers for new question - NO HACER NADA
    // Las respuestas deben mantenerse en el tablero
    console.log('📌 No reseteando respuestas - deben mantenerse visibles en ambas columnas');
    
    console.log(`🎯 Pregunta ${questionIndex + 1} actualizada:`, questionText);
}

function revealFastMoneyAnswer(answerIndex, answer, points, totalScore, hidePoints = false, participant = 1, questionIndex = 0) {
    console.log(`🎯 Revelando respuesta: Participante ${participant}, Pregunta ${questionIndex + 1}, Respuesta ${answerIndex + 1}: ${answer}`);
    
    // Usar los nuevos IDs con participante
    const participantSuffix = `P${participant}`;
    const questionNum = questionIndex + 1; // Pregunta actual (1-5)
    
    const answerEl = document.getElementById(`fmAnswer${questionNum}${participantSuffix}`);
    const pointsEl = document.getElementById(`fmPoints${questionNum}${participantSuffix}`);
    const container = document.querySelector(`[data-fm-answer="${questionIndex}"][data-participant="${participant}"]`);
    
    if(answerEl) {
        answerEl.textContent = answer;
        console.log(`✅ Actualizado texto en: fmAnswer${questionNum}${participantSuffix}`);
    } else {
        console.error(`❌ No se encontró elemento: fmAnswer${questionNum}${participantSuffix}`);
    }
    
    // Solo mostrar puntos si no están ocultos
    if(pointsEl) {
        pointsEl.textContent = hidePoints ? '???' : points;
        console.log(`✅ Actualizado puntos en: fmPoints${questionNum}${participantSuffix} = ${hidePoints ? '???' : points}`);
    } else {
        console.error(`❌ No se encontró elemento: fmPoints${questionNum}${participantSuffix}`);
    }
    
    if(container) {
        container.classList.add('revealed');
        
        // Animación de aparición
        container.style.transform = 'translateX(-20px)';
        container.style.opacity = '0.5';
        
        setTimeout(() => {
            container.style.transition = 'all 0.5s ease';
            container.style.transform = 'translateX(0)';
            container.style.opacity = '1';
        }, 100);
        
        console.log(`✅ Animación aplicada al contenedor de participante ${participant}, pregunta ${questionIndex + 1}`);
    } else {
        console.error(`❌ No se encontró contenedor: [data-fm-answer="${questionIndex}"][data-participant="${participant}"]`);
    }
    
    // NO actualizar score si los puntos están ocultos
    if(!hidePoints) {
        const scoreEl = document.querySelector('.fast-money-score');
        if(scoreEl) {
        // Obtener la meta actual del elemento de meta
        const targetElement = document.querySelector('.fast-money-target');
        const currentTarget = targetElement ? targetElement.textContent.match(/(\d+)/)?.[0] || '200' : '200';
        scoreEl.textContent = `${totalScore}/${currentTarget} PUNTOS`;
        
        // Efecto de puntos
        if(points > 0) {
            const pointsEffect = document.createElement('div');
            pointsEffect.textContent = `+${points}`;
            pointsEffect.style.cssText = `
                position: absolute;
                color: #f59e0b;
                font-size: 24px;
                font-weight: bold;
                animation: pointsEffect 2s ease-out;
                z-index: 1000;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
            `;
            
            document.body.appendChild(pointsEffect);
            
            setTimeout(() => pointsEffect.remove(), 2000);
        }
    }
    }
    
    // Actualizar score del participante (solo si no están ocultos)
    if(!hidePoints && points > 0) {
        updateParticipantScore(participant, points);
    }
    
    console.log(`✅ Respuesta revelada: ${answer} (${hidePoints ? '??? pts' : points + ' pts'}) - Participante: ${participant}`);
}

// 📊 Función para actualizar score individual de participante
function updateParticipantScore(participant, points) {
    const scoreEl = document.getElementById(`participant${participant}Score`);
    if(scoreEl) {
        const currentScore = parseInt(scoreEl.textContent) || 0;
        const newScore = currentScore + points;
        scoreEl.textContent = newScore;
        
        // Efecto visual
        scoreEl.style.animation = 'pulse 0.5s ease-in-out';
        setTimeout(() => {
            scoreEl.style.animation = '';
        }, 500);
        
        console.log(`📊 Score Participante ${participant}: ${currentScore} + ${points} = ${newScore}`);
    }
}

// 💰 Nueva función para revelar puntos totales
function revealFastMoneyTotal(totalScore, target, success) {
    console.log('💰 Revelando puntos totales:', totalScore, '/', target);
    
    // Actualizar todas las respuestas reveladas con efecto visual para ambos participantes
    for(let participant = 1; participant <= 2; participant++) {
        const revealedContainers = document.querySelectorAll(`[data-fm-answer][data-participant="${participant}"].revealed`);
        revealedContainers.forEach((container) => {
            const answerIndex = container.getAttribute('data-fm-answer');
            const questionNum = parseInt(answerIndex) + 1;
            const pointsEl = document.getElementById(`fmPoints${questionNum}P${participant}`);
            
            if(pointsEl && pointsEl.textContent === '???') {
                // Efecto de revelado de puntos
                pointsEl.style.background = '#f59e0b';
                pointsEl.style.animation = 'pulse 0.5s ease-in-out 3';
                setTimeout(() => {
                    pointsEl.style.background = '';
                    pointsEl.style.animation = '';
                }, 1500);
            }
        });
    }
    
    // 🎉 AHORA SÍ mostrar el resultado final con gran efecto
    const scoreEl = document.getElementById('fastMoneyTotalScore');
    if(scoreEl) {
        // Mostrar resultado final con animación dramática
        setTimeout(() => {
            scoreEl.textContent = `¡${totalScore}/${target} PUNTOS!`;
            scoreEl.style.color = success ? '#10b981' : '#ef4444';
            scoreEl.style.animation = 'pulse 1s ease-in-out 5';
            scoreEl.style.fontSize = '1.5em';
            scoreEl.style.fontWeight = 'bold';
            
            // Efecto adicional si ganaron
            if(success) {
                scoreEl.style.background = 'linear-gradient(45deg, #10b981, #34d399)';
                scoreEl.style.backgroundClip = 'text';
                scoreEl.style.webkitBackgroundClip = 'text';
            }
        }, 500);
        
        // Gran efecto visual
        const totalEffect = document.createElement('div');
        totalEffect.textContent = success ? `¡${totalScore} PUNTOS! ¡GANASTE!` : `${totalScore} PUNTOS - NO ALCANZÓ`;
        totalEffect.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: ${success ? '#10b981' : '#ef4444'};
            font-size: 48px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            z-index: 9999;
            animation: totalReveal 3s ease-out;
            pointer-events: none;
        `;
        
        document.body.appendChild(totalEffect);
        setTimeout(() => totalEffect.remove(), 3000);
        
        // Reset color después del efecto
        setTimeout(() => {
            scoreEl.style.color = '';
            scoreEl.style.animation = '';
        }, 5000);
    }
    
    // Sonido de éxito o fracaso  
    if(success) {
        playSuccessSound();
    }
}

function resetFastMoneyBoard() {
    // Reset question
    const questionEl = document.querySelector('.fast-money-question');
    if(questionEl) questionEl.textContent = 'Selecciona una pregunta desde el controlador';
    
    // Reset answers para ambos participantes
    for(let participant = 1; participant <= 2; participant++) {
        for(let i = 1; i <= 5; i++) {
            const answerEl = document.getElementById(`fmAnswer${i}P${participant}`);
            const pointsEl = document.getElementById(`fmPoints${i}P${participant}`);
            if(answerEl) answerEl.textContent = '---';
            if(pointsEl) pointsEl.textContent = '--';
            
            const container = document.querySelector(`[data-fm-answer="${i-1}"][data-participant="${participant}"]`);
            if(container) {
                container.classList.remove('revealed');
            }
        }
        
        // Reset score del participante
        const participantScoreEl = document.getElementById(`participant${participant}Score`);
        if(participantScoreEl) {
            participantScoreEl.textContent = '0';
        }
    }
    
    // Reset score total - solo mostrar meta
    const totalScoreEl = document.getElementById('fastMoneyTotalScore');
    if(totalScoreEl) {
        // Obtener la meta actual
        const targetElement = document.querySelector('.fast-money-target');
        const currentTarget = targetElement ? targetElement.textContent.match(/(\d+)/)?.[0] || '200' : '200';
        totalScoreEl.textContent = `META: ${currentTarget} PUNTOS`;
    }
    
    console.log('🔄 Dinero Rápido reiniciado');
}

function finishFastMoneyBoard(success, finalScore) {
    const overlay = document.getElementById('fastMoneyOverlay');
    if(!overlay) return;
    
    // Crear mensaje de finalización
    const finishMessage = document.createElement('div');
    finishMessage.style.cssText = `
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: ${success ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #ef4444, #dc2626)'};
        color: white;
        padding: 40px 60px;
        border-radius: 20px;
        font-size: 36px;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
        z-index: 10000;
        animation: finishPulse 2s ease-in-out;
    `;
    
    // Obtener la meta actual
    const targetElement = document.querySelector('.fast-money-target');
    const currentTarget = targetElement ? targetElement.textContent.match(/(\d+)/)?.[0] || '200' : '200';
    
    finishMessage.innerHTML = success 
        ? `🏆🏆🏆<br>¡¡¡FELICIDADES!!!<br>DINERO RÁPIDO COMPLETADO<br>${finalScore} PUNTOS` 
        : `😢 DINERO RÁPIDO TERMINADO<br>${finalScore}/${currentTarget} PUNTOS<br>¡Mejor suerte la próxima vez!`;
    
    overlay.appendChild(finishMessage);
    
    // Remover mensaje después de 5 segundos
    setTimeout(() => {
        if(finishMessage.parentNode) {
            finishMessage.remove();
        }
    }, 5000);
    
    console.log(`🏆 Dinero Rápido finalizado - Éxito: ${success}, Puntos: ${finalScore}`);
}

// 📊 Mostrar resumen de respuestas del dinero rápido en el tablero
function showFastMoneyResultsOnBoard(sessionData) {
    const overlay = document.getElementById('fastMoneyOverlay');
    if(!overlay) return;
    
    const resultsContainer = document.createElement('div');
    resultsContainer.style.cssText = `
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #1e293b, #334155);
        border: 3px solid #ffd700;
        border-radius: 20px;
        padding: 30px;
        max-width: 80%;
        max-height: 70%;
        overflow-y: auto;
        z-index: 10001;
        box-shadow: 0 0 50px rgba(255, 215, 0, 0.3);
    `;
    
    const questionsHtml = sessionData.questions.map((item, index) => `
        <div style="background: rgba(255, 215, 0, 0.1); margin: 15px 0; padding: 20px; border-radius: 12px; border-left: 4px solid #ffd700;">
            <div style="color: #ffd700; font-size: 24px; font-weight: bold; margin-bottom: 8px;">
                ${item.question}
            </div>
            <div style="color: white; font-size: 20px; display: flex; justify-content: space-between; align-items: center;">
                <span>→ ${item.selectedAnswer}</span>
                <span style="background: #ffd700; color: black; padding: 5px 15px; border-radius: 8px; font-weight: bold;">
                    ${item.points} pts
                </span>
            </div>
        </div>
    `).join('');
    
    resultsContainer.innerHTML = `
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #ffd700; font-size: 36px; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                🏆 RESUMEN DINERO RÁPIDO
            </h2>
        </div>
        <div style="max-height: 400px; overflow-y: auto;">
            ${questionsHtml}
        </div>
        <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 2px solid #ffd700;">
            <div style="font-size: 32px; font-weight: bold; color: #ffd700; margin-bottom: 15px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                TOTAL: ${sessionData.totalScore} PUNTOS
            </div>
            <div style="font-size: 18px; color: #94a3b8;">
                Presiona cualquier tecla para cerrar
            </div>
        </div>
    `;
    
    overlay.appendChild(resultsContainer);
    
    // Cerrar con cualquier tecla
    const handleKeyPress = (e) => {
        if(resultsContainer.parentNode) {
            resultsContainer.remove();
            document.removeEventListener('keydown', handleKeyPress);
        }
    };
    
    document.addEventListener('keydown', handleKeyPress);
    
    // Auto-cerrar después de 15 segundos
    setTimeout(() => {
        if(resultsContainer.parentNode) {
            resultsContainer.remove();
            document.removeEventListener('keydown', handleKeyPress);
        }
    }, 15000);
    
    console.log('📊 Mostrando resumen de respuestas en el tablero');
}

// 🎯 Actualizar meta del dinero rápido en el tablero
function updateFastMoneyTargetDisplay(newTarget) {
    // Actualizar el elemento de la meta
    const targetElement = document.querySelector('.fast-money-target');
    if(targetElement) {
        targetElement.textContent = `META: ${newTarget} PUNTOS`;
    }
    
    // Actualizar el display de puntuación si existe - solo mostrar meta
    const scoreElement = document.querySelector('.fast-money-score');
    if(scoreElement) {
        scoreElement.textContent = `META: ${newTarget} PUNTOS`;
    }
    
    console.log(`🎯 Meta actualizada a ${newTarget} puntos`);
}

// 🔒 Ocultar respuestas y puntos del participante anterior
function hideParticipantPoints(participant) {
    console.log(`🔒 Ocultando respuestas y puntos del participante ${participant}`);
    
    // Ocultar tanto respuestas como puntos de las 5 preguntas del participante específico
    for(let questionNum = 1; questionNum <= 5; questionNum++) {
        // Ocultar puntos
        const pointsEl = document.getElementById(`fmPoints${questionNum}P${participant}`);
        if(pointsEl && pointsEl.textContent !== '---') {
            pointsEl.textContent = '???';
            pointsEl.style.color = '#6b7280'; // Color gris para indicar que está oculto
            console.log(`🔒 Puntos ocultos en fmPoints${questionNum}P${participant}`);
        }
        
        // Ocultar respuestas
        const answerEl = document.getElementById(`fmAnswer${questionNum}P${participant}`);
        if(answerEl && answerEl.textContent !== '---') {
            answerEl.textContent = '???';
            answerEl.style.color = '#6b7280'; // Color gris para indicar que está oculto
            console.log(`🔒 Respuesta oculta en fmAnswer${questionNum}P${participant}`);
        }
    }
    
    console.log(`✅ Respuestas y puntos del participante ${participant} ocultados exitosamente`);
}

// 🎯 Revelar una respuesta específica paso a paso
function revealAnswerStep(participant, questionIndex, answer, points, stepNumber, totalSteps) {
    console.log(`🎯 Revelando paso ${stepNumber}/${totalSteps}: P${participant} Q${questionIndex + 1} - ${answer} = ${points} pts`);
    
    const questionNum = questionIndex + 1;
    const answerEl = document.getElementById(`fmAnswer${questionNum}P${participant}`);
    const pointsEl = document.getElementById(`fmPoints${questionNum}P${participant}`);
    const container = document.querySelector(`[data-fm-answer="${questionIndex}"][data-participant="${participant}"]`);
    
    // Primero mostrar la respuesta si está oculta
    if(answerEl && answerEl.textContent === '???') {
        answerEl.textContent = answer;
        answerEl.style.color = ''; // Restaurar color normal
        
        // Efecto de aparición de respuesta
        answerEl.style.transform = 'scale(1.1)';
        answerEl.style.background = '#3b82f6';
        setTimeout(() => {
            answerEl.style.transform = 'scale(1)';
            answerEl.style.background = '';
        }, 500);
    }
    
    // Luego revelar los puntos con efecto dramático
    if(pointsEl) {
        setTimeout(() => {
            pointsEl.textContent = points;
            pointsEl.style.color = ''; // Restaurar color normal
            
            // Gran efecto visual para los puntos
            pointsEl.style.background = '#f59e0b';
            pointsEl.style.transform = 'scale(1.3)';
            pointsEl.style.fontWeight = 'bold';
            pointsEl.style.animation = 'pulse 0.8s ease-in-out 3';
            
            setTimeout(() => {
                pointsEl.style.background = '';
                pointsEl.style.transform = 'scale(1)';
                pointsEl.style.animation = '';
            }, 2000);
        }, 800);
    }
    
    // Marcar contenedor como revelado
    if(container) {
        container.classList.add('revealed');
        
        // Efecto de brillo en todo el contenedor
        container.style.boxShadow = '0 0 20px rgba(245, 158, 11, 0.6)';
        setTimeout(() => {
            container.style.boxShadow = '';
        }, 2500);
    }
    
    console.log(`✅ Paso ${stepNumber}/${totalSteps} revelado: ${answer} = ${points} pts`);
}

// 📊 Actualizar scores individuales de participantes
function updateIndividualParticipantScores(participant1Score, participant2Score) {
    console.log(`📊 Actualizando scores: P1=${participant1Score}, P2=${participant2Score}`);
    
    // Actualizar score del Participante 1
    const p1ScoreElement = document.querySelector('.fast-money-overlay .participant-column:first-child .participant-score');
    if(p1ScoreElement) {
        // Efecto dramático
        p1ScoreElement.style.transition = 'all 0.8s ease';
        p1ScoreElement.style.transform = 'scale(1.2)';
        p1ScoreElement.style.color = '#f59e0b';
        
        setTimeout(() => {
            p1ScoreElement.textContent = `Score: ${participant1Score}`;
            
            // Restaurar estilo después del efecto
            setTimeout(() => {
                p1ScoreElement.style.transform = 'scale(1)';
                p1ScoreElement.style.color = '#f59e0b';
            }, 300);
        }, 400);
    }
    
    // Actualizar score del Participante 2
    const p2ScoreElement = document.querySelector('.fast-money-overlay .participant-column:last-child .participant-score');
    if(p2ScoreElement) {
        // Efecto dramático con delay
        setTimeout(() => {
            p2ScoreElement.style.transition = 'all 0.8s ease';
            p2ScoreElement.style.transform = 'scale(1.2)';
            p2ScoreElement.style.color = '#f59e0b';
            
            setTimeout(() => {
                p2ScoreElement.textContent = `Score: ${participant2Score}`;
                
                // Restaurar estilo después del efecto
                setTimeout(() => {
                    p2ScoreElement.style.transform = 'scale(1)';
                    p2ScoreElement.style.color = '#f59e0b';
                }, 300);
            }, 400);
        }, 800); // Delay para que se vea después del P1
    }
    
    console.log(`✅ Scores actualizados: Participante 1 = ${participant1Score}, Participante 2 = ${participant2Score}`);
}

// 🎉 Mostrar animación de victoria
function showVictoryAnimation(totalScore, target, difference) {
    console.log(`🎉 Mostrando animación de VICTORIA: ${totalScore}/${target} (+${difference})`);
    
    // Crear overlay de victoria
    const victoryOverlay = document.createElement('div');
    victoryOverlay.className = 'victory-overlay show-victory';
    
    const victoryContent = document.createElement('div');
    victoryContent.className = 'victory-content';
    victoryContent.innerHTML = `
        <div style="font-size: 5rem; margin-bottom: 20px;">🎉 ¡FELICIDADES! 🎉</div>
        <div style="font-size: 3rem; margin-bottom: 10px;">¡DINERO RÁPIDO COMPLETADO!</div>
        <div style="font-size: 2rem; color: #fbbf24;">${totalScore}/${target} PUNTOS</div>
        <div style="font-size: 1.5rem; color: #34d399;">+${difference} puntos de ventaja</div>
    `;
    
    victoryOverlay.appendChild(victoryContent);
    
    // Crear confetti
    for(let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.animationDelay = Math.random() * 3 + 's';
        confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
        victoryOverlay.appendChild(confetti);
    }
    
    document.body.appendChild(victoryOverlay);
    
    // Remover después de la animación
    setTimeout(() => {
        if(victoryOverlay.parentNode) {
            victoryOverlay.parentNode.removeChild(victoryOverlay);
        }
    }, 4000);
    
    console.log('🎊 Animación de victoria iniciada');
}

// 😔 Mostrar animación de derrota
function showDefeatAnimation(totalScore, target, difference) {
    console.log(`😔 Mostrando animación de DERROTA: ${totalScore}/${target} (-${difference})`);
    
    // Crear overlay de derrota
    const defeatOverlay = document.createElement('div');
    defeatOverlay.className = 'defeat-overlay show-defeat';
    
    const defeatContent = document.createElement('div');
    defeatContent.className = 'defeat-content';
    defeatContent.innerHTML = `
        <div style="font-size: 4rem; margin-bottom: 20px;">😔 ¡Tan Cerca! 😔</div>
        <div style="font-size: 2.5rem; margin-bottom: 10px;">¡Mejor suerte la próxima vez!</div>
        <div style="font-size: 2rem; color: #fbbf24;">${totalScore}/${target} PUNTOS</div>
        <div style="font-size: 1.5rem; color: #ef4444;">Solo faltaron ${difference} puntos</div>
    `;
    
    defeatOverlay.appendChild(defeatContent);
    document.body.appendChild(defeatOverlay);
    
    // Remover después de la animación
    setTimeout(() => {
        if(defeatOverlay.parentNode) {
            defeatOverlay.parentNode.removeChild(defeatOverlay);
        }
    }, 3000);
    
    console.log('💔 Animación de derrota iniciada');
}

</script>

</body>
</html>