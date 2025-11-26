<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Partidas - 100 Programadores Dijieron</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/questions.css')
    <style>
        .games-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .game-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(102, 252, 241, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .game-card:hover {
            border-color: rgba(102, 252, 241, 0.5);
            background: rgba(255, 255, 255, 0.05);
        }
        
        .game-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .game-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
        }
        
        .game-actions {
            display: flex;
            gap: 10px;
        }
        
        .questions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }
        
        .questions-section {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 15px;
        }
        
        .section-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--accent2);
            border-bottom: 1px solid rgba(102, 252, 241, 0.3);
            padding-bottom: 5px;
        }
        
        .question-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
            border-left: 3px solid var(--accent);
        }
        
        .question-name {
            font-weight: 600;
            color: white;
        }
        
        .question-text {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 5px;
        }
        
        .question-meta {
            font-size: 0.8rem;
            color: rgba(102, 252, 241, 0.7);
            margin-top: 5px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }
        
        .modal-content {
            background: var(--bg);
            border: 1px solid rgba(102, 252, 241, 0.3);
            border-radius: 12px;
            margin: 2% auto;
            padding: 30px;
            width: 90%;
            max-width: 1200px;
            max-height: 90%;
            overflow-y: auto;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        
        .question-selector {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 20px;
        }
        
        .selector-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .selected-questions {
            min-height: 300px;
            background: rgba(255, 255, 255, 0.02);
            border: 2px dashed rgba(102, 252, 241, 0.3);
            border-radius: 8px;
            padding: 15px;
        }
        
        .available-questions {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .selectable-question {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(102, 252, 241, 0.2);
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .selectable-question:hover {
            background: rgba(102, 252, 241, 0.1);
            border-color: rgba(102, 252, 241, 0.5);
        }
        
        .selectable-question.selected {
            background: rgba(102, 252, 241, 0.2);
            border-color: var(--accent);
        }
        
        .selected-question {
            background: rgba(102, 252, 241, 0.1);
            border: 1px solid var(--accent);
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .remove-question {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #ef4444;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 0.8rem;
            cursor: pointer;
        }
        
        .status-indicator {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-waiting {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
        
        .no-games-message {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="games-container">
        <header style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="color: var(--accent); margin: 0; font-size: 2rem;">🎮 Gestión de Partidas</h1>
                    <p style="color: rgba(255,255,255,0.7); margin: 5px 0 0 0;">Organiza tus partidas con 3 rondas + dinero rápido</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button id="createGameBtn" class="btn btn-primary">
                        ➕ Nueva Partida
                    </button>
                    <a href="/questions" class="btn" style="background: rgba(102,252,241,0.1);">
                        📚 Banco de Preguntas
                    </a>
                    <a href="/controller" class="btn" style="background: rgba(102,252,241,0.1);">
                        🎮 Controlador
                    </a>
                </div>
            </div>
        </header>

        <div id="gamesContainer">
            <div class="no-games-message">
                <div>📝 No hay partidas creadas</div>
                <p style="margin-top: 15px; font-size: 1rem;">Crea tu primera partida para organizar 3 rondas + dinero rápido</p>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar partida -->
    <div id="gameModal" class="modal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 id="modalTitle" style="color: var(--accent); margin: 0;">➕ Nueva Partida</h2>
                <button id="closeModal" style="background: transparent; border: none; color: rgba(255,255,255,0.6); font-size: 1.5rem; cursor: pointer;">✖</button>
            </div>

            <form id="gameForm">
                <div style="margin-bottom: 20px;">
                    <label for="gameName" style="display: block; margin-bottom: 8px; font-weight: 600;">Nombre de la Partida:</label>
                    <input type="text" id="gameName" required style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid rgba(102,252,241,0.3); background: rgba(0,0,0,0.3); color: white;" placeholder="Ej: Partida Campeonato 2025">
                </div>

                <div class="form-grid">
                    <!-- Preguntas de Ronda -->
                    <div class="question-selector">
                        <div class="selector-header">
                            <h3 style="color: var(--accent2); margin: 0;">🎯 Rondas Normales (3)</h3>
                            <span style="font-size: 0.9rem; color: rgba(255,255,255,0.6);">Una pregunta por ronda</span>
                        </div>
                        <div class="selected-questions" id="roundQuestionsSelected">
                            <div style="text-align: center; color: rgba(255,255,255,0.5); padding: 20px;">
                                Selecciona 3 preguntas de tipo "Ronda" para las 3 rondas normales
                            </div>
                        </div>
                        <h4 style="margin: 20px 0 10px 0; color: rgba(255,255,255,0.8);">Disponibles:</h4>
                        <div class="available-questions" id="roundQuestionsAvailable">
                            <!-- Se llenan dinámicamente -->
                        </div>
                    </div>

                    <!-- Preguntas de Dinero Rápido -->
                    <div class="question-selector">
                        <div class="selector-header">
                            <h3 style="color: var(--accent2); margin: 0;">💰 Dinero Rápido (5)</h3>
                            <span style="font-size: 0.9rem; color: rgba(255,255,255,0.6);">Ronda especial</span>
                        </div>
                        <div class="selected-questions" id="fastMoneyQuestionsSelected">
                            <div style="text-align: center; color: rgba(255,255,255,0.5); padding: 20px;">
                                Selecciona 5 preguntas de tipo "Dinero Rápido"
                            </div>
                        </div>
                        <h4 style="margin: 20px 0 10px 0; color: rgba(255,255,255,0.8);">Disponibles:</h4>
                        <div class="available-questions" id="fastMoneyQuestionsAvailable">
                            <!-- Se llenan dinámicamente -->
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; border-top: 1px solid rgba(102,252,241,0.2); padding-top: 20px;">
                    <button type="button" id="cancelBtn" class="btn" style="background: rgba(0,0,0,0.3);">Cancelar</button>
                    <button type="submit" class="btn btn-primary">💾 Guardar Partida</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Variables globales
        let availableRoundQuestions = [];
        let availableFastMoneyQuestions = [];
        let selectedRoundQuestions = [];
        let selectedFastMoneyQuestions = [];
        let games = [];

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            loadGames();
            loadQuestions();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('createGameBtn').addEventListener('click', openCreateModal);
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('cancelBtn').addEventListener('click', closeModal);
            document.getElementById('gameForm').addEventListener('submit', handleGameSubmit);
        }

        async function loadGames() {
            try {
                const response = await fetch('/api/games');
                games = await response.json();
                renderGames();
            } catch (error) {
                console.error('Error cargando partidas:', error);
            }
        }

        async function loadQuestions() {
            try {
                const [roundResponse, fastMoneyResponse] = await Promise.all([
                    fetch('/api/questions/type/round'),
                    fetch('/api/questions/type/fast_money')
                ]);
                
                const roundData = await roundResponse.json();
                const fastMoneyData = await fastMoneyResponse.json();
                
                availableRoundQuestions = roundData.questions || [];
                availableFastMoneyQuestions = fastMoneyData.questions || [];
                
                renderAvailableQuestions();
            } catch (error) {
                console.error('Error cargando preguntas:', error);
            }
        }

        function renderGames() {
            const container = document.getElementById('gamesContainer');
            
            if (!games || games.length === 0) {
                container.innerHTML = `
                    <div class="no-games-message">
                        <div>📝 No hay partidas creadas</div>
                        <p style="margin-top: 15px; font-size: 1rem;">Crea tu primera partida para organizar 3 rondas + dinero rápido</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = games.map(game => `
                <div class="game-card">
                    <div class="game-header">
                        <div>
                            <div class="game-title">${escapeHtml(game.name)}</div>
                            <div style="margin-top: 5px;">
                                <span class="status-indicator status-${game.status}">${getStatusText(game.status)}</span>
                                <span style="margin-left: 10px; color: rgba(255,255,255,0.6); font-size: 0.9rem;">
                                    Creada: ${new Date(game.created_at).toLocaleDateString()}
                                </span>
                            </div>
                        </div>
                        <div class="game-actions">
                            <button onclick="loadGameToController(${game.id})" class="btn" style="background: linear-gradient(135deg, #10b981, #059669);" title="Cargar partida en el controlador">
                                🎮 Cargar al Juego
                            </button>
                        </div>
                    </div>

                    <div class="questions-grid">
                        <div class="questions-section">
                            <div class="section-title">🎯 Rondas Normales (3)</div>
                            ${renderGameQuestions(game, 'round')}
                        </div>
                        <div class="questions-section">
                            <div class="section-title">💰 Dinero Rápido (5)</div>
                            ${renderGameQuestions(game, 'fast_money')}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderGameQuestions(game, type) {
            if (!game.questions) return '<div style="color: rgba(255,255,255,0.5);">Sin preguntas</div>';
            
            const questions = game.questions.filter(q => q.pivot && q.pivot.question_type === type);
            
            if (questions.length === 0) {
                return '<div style="color: rgba(255,255,255,0.5);">Sin preguntas</div>';
            }

            return questions.map(question => `
                <div class="question-item">
                    <div class="question-name">${escapeHtml(question.name)}</div>
                    <div class="question-text">${escapeHtml(question.question_text)}</div>
                    <div class="question-meta">
                        ${question.category} • ${question.answers ? question.answers.length : 0} respuestas
                        ${type === 'round' ? ` • Ronda ${question.pivot.round_number}` : ''}
                    </div>
                </div>
            `).join('');
        }

        function getStatusText(status) {
            const statusMap = {
                'waiting': 'En espera',
                'in_progress': 'En progreso',
                'finished': 'Terminada'
            };
            return statusMap[status] || status;
        }

        function openCreateModal() {
            document.getElementById('modalTitle').textContent = '➕ Nueva Partida';
            document.getElementById('gameForm').reset();
            selectedRoundQuestions = [];
            selectedFastMoneyQuestions = [];
            renderSelectedQuestions();
            renderAvailableQuestions();
            document.getElementById('gameModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('gameModal').style.display = 'none';
        }

        function renderAvailableQuestions() {
            // Renderizar preguntas de ronda disponibles
            const roundContainer = document.getElementById('roundQuestionsAvailable');
            roundContainer.innerHTML = availableRoundQuestions.map(question => `
                <div class="selectable-question" onclick="selectQuestion('round', ${question.id})">
                    <div style="font-weight: 600;">${escapeHtml(question.name)}</div>
                    <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-top: 5px;">${escapeHtml(question.question_text)}</div>
                    <div style="font-size: 0.8rem; color: rgba(102,252,241,0.7); margin-top: 5px;">
                        ${question.category} • ${question.answers ? question.answers.length : 0} respuestas
                    </div>
                </div>
            `).join('');

            // Renderizar preguntas de dinero rápido disponibles
            const fastMoneyContainer = document.getElementById('fastMoneyQuestionsAvailable');
            fastMoneyContainer.innerHTML = availableFastMoneyQuestions.map(question => `
                <div class="selectable-question" onclick="selectQuestion('fast_money', ${question.id})">
                    <div style="font-weight: 600;">${escapeHtml(question.name)}</div>
                    <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8); margin-top: 5px;">${escapeHtml(question.question_text)}</div>
                    <div style="font-size: 0.8rem; color: rgba(102,252,241,0.7); margin-top: 5px;">
                        ${question.category} • ${question.answers ? question.answers.length : 0} respuestas
                    </div>
                </div>
            `).join('');
        }

        function selectQuestion(type, questionId) {
            if (type === 'round') {
                if (selectedRoundQuestions.length >= 3) {
                    alert('Ya has seleccionado 3 preguntas para las rondas normales');
                    return;
                }
                if (!selectedRoundQuestions.includes(questionId)) {
                    selectedRoundQuestions.push(questionId);
                }
            } else if (type === 'fast_money') {
                if (selectedFastMoneyQuestions.length >= 5) {
                    alert('Ya has seleccionado 5 preguntas para el dinero rápido');
                    return;
                }
                if (!selectedFastMoneyQuestions.includes(questionId)) {
                    selectedFastMoneyQuestions.push(questionId);
                }
            }
            
            renderSelectedQuestions();
            updateAvailableQuestionsDisplay();
        }

        function removeQuestion(type, questionId) {
            if (type === 'round') {
                selectedRoundQuestions = selectedRoundQuestions.filter(id => id !== questionId);
            } else if (type === 'fast_money') {
                selectedFastMoneyQuestions = selectedFastMoneyQuestions.filter(id => id !== questionId);
            }
            
            renderSelectedQuestions();
            updateAvailableQuestionsDisplay();
        }

        function renderSelectedQuestions() {
            // Renderizar preguntas de ronda seleccionadas
            const roundContainer = document.getElementById('roundQuestionsSelected');
            if (selectedRoundQuestions.length === 0) {
                roundContainer.innerHTML = '<div style="text-align: center; color: rgba(255,255,255,0.5); padding: 20px;">Selecciona 3 preguntas de tipo "Ronda"</div>';
            } else {
                roundContainer.innerHTML = selectedRoundQuestions.map((questionId, index) => {
                    const question = availableRoundQuestions.find(q => q.id === questionId);
                    return `
                        <div class="selected-question">
                            <div>
                                <div style="font-weight: 600;">Ronda ${index + 1}: ${escapeHtml(question.name)}</div>
                                <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8);">${escapeHtml(question.question_text)}</div>
                            </div>
                            <button class="remove-question" onclick="removeQuestion('round', ${questionId})">✖</button>
                        </div>
                    `;
                }).join('');
            }

            // Renderizar preguntas de dinero rápido seleccionadas
            const fastMoneyContainer = document.getElementById('fastMoneyQuestionsSelected');
            if (selectedFastMoneyQuestions.length === 0) {
                fastMoneyContainer.innerHTML = '<div style="text-align: center; color: rgba(255,255,255,0.5); padding: 20px;">Selecciona 5 preguntas de tipo "Dinero Rápido"</div>';
            } else {
                fastMoneyContainer.innerHTML = selectedFastMoneyQuestions.map((questionId, index) => {
                    const question = availableFastMoneyQuestions.find(q => q.id === questionId);
                    return `
                        <div class="selected-question">
                            <div>
                                <div style="font-weight: 600;">Pregunta ${index + 1}: ${escapeHtml(question.name)}</div>
                                <div style="font-size: 0.9rem; color: rgba(255,255,255,0.8);">${escapeHtml(question.question_text)}</div>
                            </div>
                            <button class="remove-question" onclick="removeQuestion('fast_money', ${questionId})">✖</button>
                        </div>
                    `;
                }).join('');
            }
        }

        function updateAvailableQuestionsDisplay() {
            // Actualizar estilos de preguntas disponibles
            document.querySelectorAll('#roundQuestionsAvailable .selectable-question').forEach((element, index) => {
                const question = availableRoundQuestions[index];
                if (selectedRoundQuestions.includes(question.id)) {
                    element.classList.add('selected');
                } else {
                    element.classList.remove('selected');
                }
            });

            document.querySelectorAll('#fastMoneyQuestionsAvailable .selectable-question').forEach((element, index) => {
                const question = availableFastMoneyQuestions[index];
                if (selectedFastMoneyQuestions.includes(question.id)) {
                    element.classList.add('selected');
                } else {
                    element.classList.remove('selected');
                }
            });
        }

        async function handleGameSubmit(e) {
            e.preventDefault();
            
            const gameName = document.getElementById('gameName').value.trim();
            
            if (!gameName) {
                alert('Por favor ingresa un nombre para la partida');
                return;
            }
            
            if (selectedRoundQuestions.length !== 3) {
                alert('Debes seleccionar exactamente 3 preguntas para las rondas normales');
                return;
            }
            
            if (selectedFastMoneyQuestions.length !== 5) {
                alert('Debes seleccionar exactamente 5 preguntas para el dinero rápido');
                return;
            }

            try {
                const response = await fetch('/api/games', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: gameName,
                        round_questions: selectedRoundQuestions,
                        fast_money_questions: selectedFastMoneyQuestions
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('✅ Partida creada exitosamente');
                    closeModal();
                    loadGames();
                } else {
                    alert('Error: ' + (data.error || 'No se pudo crear la partida'));
                }
            } catch (error) {
                console.error('Error creando partida:', error);
                alert('Error de conexión al crear la partida');
            }
        }

        async function loadGameToController(gameId) {
            if (confirm('¿Cargar esta partida completa al controlador del juego?')) {
                try {
                    const response = await fetch(`/api/games/${gameId}/load`);
                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Guardar los datos de la partida en el almacenamiento temporal para que el controller los use
                        localStorage.setItem('loaded_game_data', JSON.stringify(data));
                        alert(`✅ Partida "${data.game.name}" cargada exitosamente.\n\n📋 Datos disponibles:\n• ${data.round_questions.length} preguntas de ronda\n• ${data.fast_money_questions.length} preguntas de dinero rápido\n\nAhora ve al Controlador para usar esta partida.`);
                        
                        // Opcional: abrir el controlador automáticamente
                        window.open('/controller', '_blank');
                    } else {
                        alert('Error: ' + (data.error || 'No se pudo cargar la partida'));
                    }
                } catch (error) {
                    console.error('Error cargando partida:', error);
                    alert('Error de conexión al cargar la partida');
                }
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    </script>
</body>
</html>