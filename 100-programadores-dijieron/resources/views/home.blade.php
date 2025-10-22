<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1100100 Devs Dijeron - Inicio</title>
    <style>
        :root {
            --bg-dark: #0a0e27;
            --bg-card: #131829;
            --accent: #00d9ff;
            --accent-purple: #7b61ff;
            --text-light: #e0f2fe;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--bg-dark) 0%, #0f172a 100%);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-purple) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-shadow: 0 0 30px rgba(0, 217, 255, 0.3);
        }

        .subtitle {
            font-size: 20px;
            color: var(--text-muted);
            font-weight: 300;
        }

        .card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 217, 255, 0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '▶';
            font-size: 16px;
        }

        .description {
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .feature {
            background: rgba(0, 217, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(0, 217, 255, 0.1);
            transition: all 0.3s ease;
        }

        .feature:hover {
            background: rgba(0, 217, 255, 0.1);
            border-color: rgba(0, 217, 255, 0.3);
            transform: translateY(-2px);
        }

        .feature-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .feature-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 18px 40px;
            font-size: 18px;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        .btn-controller {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-controller:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(239, 68, 68, 0.4);
        }

        .btn-board {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-purple) 100%);
            color: white;
        }

        .btn-board:hover {
            background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(0, 217, 255, 0.4);
        }

        .icon {
            font-size: 24px;
        }

        .note {
            background: rgba(123, 97, 255, 0.1);
            border-left: 4px solid var(--accent-purple);
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .note-title {
            font-weight: 600;
            color: var(--accent-purple);
            margin-bottom: 8px;
        }

        .note-text {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 36px;
            }

            .card {
                padding: 25px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">1100100 Devs Dijeron</h1>
            <p class="subtitle">El juego de encuestas más emocionante</p>
        </div>

        <div class="card">
            <h2 class="section-title">¿Cómo se juega?</h2>
            <p class="description">
                "1100100 Devs Dijeron" es un juego inspirado en el clásico programa de televisión. 
                Dos familias compiten respondiendo preguntas basadas en encuestas realizadas a 100 personas. 
                El objetivo es adivinar las respuestas más populares para acumular puntos y ganar la ronda.
            </p>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">🎮</div>
                    <div class="feature-title">Controller</div>
                    <div class="feature-desc">
                        Interfaz de control para el presentador. Gestiona preguntas, respuestas, puntos y X's.
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">📺</div>
                    <div class="feature-title">Tablero</div>
                    <div class="feature-desc">
                        Vista pública que muestra las respuestas, puntos acumulados y marcadores de los equipos.
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">⚡</div>
                    <div class="feature-title">Tiempo Real</div>
                    <div class="feature-desc">
                        Sincronización instantánea entre controller y tablero para una experiencia fluida.
                    </div>
                </div>
            </div>

            <div class="note">
                <div class="note-title">💡 Consejo</div>
                <div class="note-text">
                    Abre el <strong>Tablero</strong> en una pantalla grande o proyector para que todos lo vean. 
                    Usa el <strong>Controller</strong> en tu dispositivo para controlar el juego de forma privada.
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="section-title">Reglas del Juego</h2>
            <div class="description">
                <ul style="list-style: none; padding-left: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <strong style="color: var(--accent);">1.</strong> Las familias se turnan para dar respuestas
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <strong style="color: var(--accent);">2.</strong> Los aciertos suman puntos al banco de la ronda
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <strong style="color: var(--accent);">3.</strong> Tres X's (❌) y se pierde el turno
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <strong style="color: var(--accent);">4.</strong> La familia ganadora se lleva todos los puntos de la ronda
                    </li>
                    <li style="padding: 10px 0;">
                        <strong style="color: var(--accent);">5.</strong> Gana quien tenga más puntos al final del juego
                    </li>
                </ul>
            </div>
        </div>

        <div class="buttons">
            <a href="/controller" class="btn btn-controller" target="_blank">
                <span class="icon">🎮</span>
                Abrir Controller
            </a>
            <a href="/tablero" class="btn btn-board" target="_blank">
                <span class="icon">📺</span>
                Abrir Tablero
            </a>
        </div>
    </div>
</body>
</html>
