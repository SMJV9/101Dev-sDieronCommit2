<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controller - 1100100 Devs Dijeron</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/controller.css')
    <style>
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .blocked-answer {
            background: rgba(239, 68, 68, 0.1) !important;
            border-left: 3px solid #ef4444;
        }
    </style>
</head>
<body>
<header>
    <h1>1100100 Devs Dijeron - Controller</h1>
    <div>Estado: <span id="state">Listo</span></div>
</header>
<main>
    <p>Define las respuestas y luego usa los botones para revelarlas en el tablero.</p>

    <section>
        <label>Pregunta:</label>
        <input id="question" style="width:60%" placeholder="" />
        <div style="margin-top:10px;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <label style="margin-bottom:0">Elegir pregunta:</label>
            <select id="questionDropdown" style="min-width:360px">
                <option value="">-- seleccionar pregunta --</option>

            </select>
            <button id="loadSelectedQuestion">Cargar</button>
            <button id="refreshQuestions" title="Actualizar lista" style="background:rgba(0,0,0,0.5);color:var(--text);border:1px solid rgba(102,252,241,0.3)">↻</button>
            <span id="dropdownInfo" style="font-size:12px;color:var(--muted)">Banco de preguntas disponibles</span>
            <span id="dropdownStatus" style="font-size:12px;color:#94a3b8"></span>
        </div>
    </section>

    <section style="margin-top:12px">
        <!-- <button id="setDefaults">Cargar ejemplo</button> -->
        <button id="sendInit">Enviar al tablero</button>
        <button id="resend">Reenviar estado</button>
        <button id="testConnection" style="background:#10b981;color:white">🔗 Test conexión</button>
        <button id="reset">Reset</button>
        <button id="newGame" style="margin-left:6px;background:linear-gradient(135deg,#f59e0b 0%, #d97706 100%)">Nueva partida</button>
        <!-- <button id="addAnswer">Agregar respuesta</button> -->
        <span id="syncStatus" style="margin-left:12px;font-size:12px;color:#94a3b8">Sin actividad</span>
    </section>
    
    <section style="margin-top:12px">
        <h3>Ronda</h3>
        <div style="display:flex;gap:12px;align-items:center;margin-bottom:10px">
            <div style="flex:1">
                <label>Equipo 1:</label>
                <input id="team1Input" placeholder="Nombre del Equipo 1" style="width:100%;margin-top:4px" value="Equipo 1" />
            </div>
            <div style="flex:1">
                <label>Equipo 2:</label>
                <input id="team2Input" placeholder="Nombre del Equipo 2" style="width:100%;margin-top:4px" value="Equipo 2" />
            </div>
        </div>
        <span id="teamsValidation" style="color:#ef4444;font-weight:600;display:none">Ingresa el nombre de ambos equipos</span>
        <div style="margin-top:8px;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <label style="font-weight:600">Multiplicador:</label>
            <button id="multiplier1" class="multiplier-btn active" data-multiplier="1">x1</button>
            <button id="multiplier2" class="multiplier-btn" data-multiplier="2">x2 Doble</button>
            <button id="multiplier3" class="multiplier-btn" data-multiplier="3">x3 Triple</button>
        </div>
        <div style="margin-top:8px">
            <button id="startRound">Iniciar ronda</button>
            <button id="nextRound" title="Usa los mismos equipos y conserva el marcador">Siguiente ronda</button>
            <button id="finishRound" style="margin-left:10px;background:linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)">🏁 Finalizar ronda</button>
            <a href="/questions" style="margin-left:12px;color:var(--accent);text-decoration:none;font-weight:700;display:inline-flex;align-items:center;padding:5px 8px;background:rgba(102,252,241,0.1);border-radius:4px;font-size:13px;">📚 Banco de Preguntas</a>
                <button id="fastMoneyBtn" disabled style="margin-left:6px;background:#6b7280;color:#9ca3af;opacity:0.6;cursor:not-allowed">💰 DINERO RÁPIDO</button>
            <button id="addStrike" style="margin-left:10px;background:#ef4444;color:white;">❌ X</button>
            <span id="strikeCount" style="margin-left:10px;font-size:14px;font-weight:bold;color:#ef4444;">X: 0/3</span>
            <span id="roundNumber" style="margin-left:10px;font-size:14px;font-weight:bold;color:var(--accent)">Ronda: 1</span>
        </div>
        <div id="turnControls" style="display:none">
            <label style="margin-right:8px">Turno:</label>
            <div id="activeTeamButtons" style="display:flex;gap:8px;flex-wrap:wrap"></div>
        </div>
        <!-- Timer de respuesta -->
        <div id="timerContainer" style="display:none;margin-top:12px;padding:12px;border-radius:8px;background:rgba(102,252,241,0.05);border:1px solid rgba(102,252,241,0.3)">
            <div style="display:flex;align-items:center;gap:12px;justify-content:space-between;flex-wrap:wrap">
                <div style="flex:1">
                    <div style="font-size:13px;color:var(--muted);margin-bottom:4px;text-transform:uppercase;font-weight:600">⏱️ Tiempo de respuesta</div>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div id="timerDisplay" style="font-size:48px;font-weight:900;color:var(--accent);text-shadow:0 0 20px rgba(102,252,241,0.6);font-variant-numeric:tabular-nums;min-width:120px">30</div>
                        <div style="display:flex;flex-direction:column;gap:4px">
                            <button id="startTimer" style="padding:6px 12px;font-size:12px">▶️ Iniciar</button>
                            <button id="pauseTimer" style="padding:6px 12px;font-size:12px;display:none">⏸️ Pausar</button>
                            <button id="resetTimer" style="padding:6px 12px;font-size:12px;background:rgba(122,128,132,0.3);color:var(--text)">🔄 Reset</button>
                        </div>
                    </div>
                </div>
                <div style="flex:0 0 auto">
                    <label style="font-size:11px;margin-bottom:4px">Segundos:</label>
                    <input type="number" id="timerSeconds" value="10" min="5" max="300" style="width:80px;padding:6px;font-size:14px;font-weight:700" />
                </div>
            </div>
            <div id="timerProgress" style="margin-top:8px;height:6px;background:rgba(0,0,0,0.3);border-radius:3px;overflow:hidden">
                <div id="timerProgressBar" style="height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width 0.3s linear,background 0.3s;width:100%"></div>
            </div>
        </div>
        <div id="roundAssign" style="display:none;margin-top:8px;padding:8px;border-radius:6px;background:#f1f5f9">
            <div id="roundReadyText" style="font-weight:700;color:#0b1220;margin-bottom:6px">La ronda terminó. ¿Qué familia gana los puntos?</div>
            <div id="roundTeamButtons" style="display:flex;gap:8px;flex-wrap:wrap"></div>
        </div>
        <div id="stealAttempt" style="display:none;margin-top:8px;padding:12px;border-radius:6px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3)">
            <div style="font-weight:700;color:#0b1220;margin-bottom:8px">🎯 Robo de puntos: <span id="stealTeamName" style="color:#ef4444"></span> tiene oportunidad de responder</div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:10px">⚠️ Primero revela la respuesta que dieron. Si es correcta, dale en "Acertó".</div>
            <div style="display:flex;gap:8px">
                <button id="stealSuccess" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%)">✓ Acertó</button>
                <button id="stealFail" style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%)">✗ Falló</button>
            </div>
        </div>
        <div id="teamScoresDisplay" style="margin-top:10px;display:flex;gap:8px"></div>
    </section>
    
    <section class="answers" id="answers"></section>
</main>

<script>
// communication: prefer BroadcastChannel, fallback to localStorage events
let channel = null;
let usingBroadcast = false;

// Función para inicializar conexión y terminal
function initializeConnection() {
    try {
        if (typeof BroadcastChannel !== 'undefined') {
            channel = new BroadcastChannel('game-100mx');
            usingBroadcast = true;
            console.debug('[controller] BroadcastChannel opened', channel);
            const connTypeEl = document.getElementById('connType');
            if(connTypeEl) connTypeEl.textContent = 'broadcastchannel --status=connected ✓';
        }
    } catch(e){
        console.debug('[controller] BroadcastChannel not available', e);
        const connTypeEl = document.getElementById('connType');
        if(connTypeEl) connTypeEl.textContent = 'broadcastchannel --status=error ❌';
    }
    
    // Configurar listeners después de inicializar
    setupMessageListeners();
}

// Function to unlock Fast Money mode
function unlockFastMoney() {
    const fastMoneyBtn = document.getElementById('fastMoneyBtn');
    if(fastMoneyBtn) {
        fastMoneyBtn.disabled = false;
        fastMoneyBtn.style.opacity = '1';
        fastMoneyBtn.style.cursor = 'pointer';
        fastMoneyBtn.style.background = 'linear-gradient(90deg,#f59e0b,#d97706)';
        fastMoneyBtn.innerHTML = '💰 DINERO RÁPIDO <span style="font-size:10px;opacity:0.8">¡DISPONIBLE!</span>';
        
        // Add glow effect
        fastMoneyBtn.style.boxShadow = '0 0 20px rgba(245, 158, 11, 0.5)';
        
        showTerminalMessage('fast-money --status=unlocked --condition=round3-complete 🔓');
    }
}

// fallback send/receive via localStorage
function sendMessage(msg){
    console.log('📤 [controller] Enviando mensaje:', msg);
    if (usingBroadcast && channel) {
        console.log('📡 Enviando via BroadcastChannel');
        return channel.postMessage(msg);
    }
    try{ 
        console.log('💾 Enviando via localStorage');
        localStorage.setItem('game-100mx', JSON.stringify({msg, ts:Date.now()})); 
    }catch(e){
        console.error('[controller] localStorage send failed', e)
    }
}

function addStorageListener(fn){
    window.addEventListener('storage', (ev)=>{
        if (!ev.key) return;
        if (ev.key !== 'game-100mx') return;
        try{ const data = JSON.parse(ev.newValue); fn(data.msg); }catch(e){}
    });
}

// Función para configurar los listeners
function setupMessageListeners() {
    // if BroadcastChannel present, wire up its onmessage; otherwise use storage listener
    if (usingBroadcast && channel){
        channel.onmessage = (ev)=>{ 
            console.debug('[controller] received (bc)', ev.data); 
            handleIncoming(ev.data); 
        };
        console.debug('[controller] BroadcastChannel listener configured');
    } else {
        addStorageListener((data)=>{ 
            console.debug('[controller] received (storage)', data); 
            handleIncoming(data); 
        });
        const connTypeEl = document.getElementById('connType');
        if(connTypeEl) connTypeEl.textContent = 'localstorage --fallback=true ⚠️';
        console.debug('[controller] localStorage listener configured');
    }
}
const answersEl = document.getElementById('answers');
const stateEl = document.getElementById('state');
const questionEl = document.getElementById('question');
const questionDropdownEl = document.getElementById('questionDropdown');
const loadSelectedBtn = document.getElementById('loadSelectedQuestion');
const refreshQuestionsBtn = document.getElementById('refreshQuestions');
const dropdownStatusEl = document.getElementById('dropdownStatus');
let dropdownQuestionsIndex = [];

// Build API base from current origin to preserve dev port (e.g., :8000)
const API_QUESTIONS_BASE = `${window.location.origin}/api/questions`;
// logs removed by user request
const ctrlLogs = null;

// ===== Sync status & ACK handling =====
const syncStatusEl = document.getElementById('syncStatus');
let lastAckId = null; let ackTimer = null;
function setSyncStatus(state, text){
    if(!syncStatusEl) return;
    syncStatusEl.textContent = text || '';
    if(state === 'ok') syncStatusEl.style.color = '#22c55e';
    else if(state === 'sync') syncStatusEl.style.color = '#f59e0b';
    else if(state === 'error') syncStatusEl.style.color = '#ef4444';
    else syncStatusEl.style.color = '#94a3b8';
}

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

// Keep a reference to the original sender and wrap it to request ACKs
const __rawSendMessage = sendMessage;
sendMessage = function(msg){
    try{
        if(msg && typeof msg === 'object'){
            const id = 'm-' + Date.now() + '-' + Math.random().toString(36).slice(2);
            msg._id = id; msg._ack = true; lastAckId = id;
        }
        setSyncStatus('sync', 'Sincronizando…');
        if(ackTimer) clearTimeout(ackTimer);
        ackTimer = setTimeout(()=> setSyncStatus('error', 'Error de conexión'), 2500);
        return __rawSendMessage(msg);
    }catch(e){
        setSyncStatus('error', 'Error de conexión');
    }
};

// ===== Dropdown: fetch and load individual questions =====
async function fetchQuestionsForDropdown(){
    if(!questionDropdownEl) return;
    try{
        if(dropdownStatusEl) dropdownStatusEl.textContent = 'Cargando…';
        
        // Fetch questions
        const questionsRes = await fetch(API_QUESTIONS_BASE);
        const questions = await questionsRes.json();
        
        // Filter only active questions
        const activeQuestions = Array.isArray(questions) ? questions.filter(q => q && q.is_active) : [];
        
        // Clear dropdown and add options
        questionDropdownEl.innerHTML = '<option value="">-- seleccionar pregunta --</option>';
        dropdownQuestionsIndex = {};
        
        // Add individual question options
        activeQuestions.forEach(question => {
            const opt = document.createElement('option');
            opt.value = question.id;
            const typeIcon = question.question_type === 'fast_money' ? '⚡' : '🎯';
            const categoryIcon = question.category === 'dev' ? '💻' : '📚';
            opt.textContent = `${typeIcon} ${question.name} (${categoryIcon} ${question.category === 'dev' ? 'Dev' : 'General'})`;
            questionDropdownEl.appendChild(opt);
            
            // Store question data for loading
            dropdownQuestionsIndex[question.id] = question;
        });
        
        if(dropdownStatusEl){
            dropdownStatusEl.style.color = activeQuestions.length ? '#22c55e' : '#f59e0b';
            dropdownStatusEl.textContent = activeQuestions.length ? `${activeQuestions.length} pregunta(s)` : 'No hay preguntas activas';
        }
    }catch(e){
        console.error('Error fetching questions for dropdown', e);
        if(dropdownStatusEl){ dropdownStatusEl.style.color = '#ef4444'; dropdownStatusEl.textContent = 'Error cargando lista'; }
    }
}

async function loadSelectedQuestion(){
    if(!questionDropdownEl) return;
    const questionId = questionDropdownEl.value;
    if(!questionId){ alert('Selecciona una pregunta'); return; }
    
    try{
        const res = await fetch(`${API_QUESTIONS_BASE}/${questionId}/load`);
        const payload = await res.json();
        if(!payload || !payload.success){ 
            alert('No se pudo cargar la pregunta'); 
            return; 
        }
        
        questionEl.value = payload.question || '';
        answers = (payload.answers || []).map(a => ({text:a.text, count:a.count, revealed:false, correct:false}));
        answers.sort((a,b)=> b.count - a.count);
        render();
        
        const initPayload = {answers, state:'Pregunta cargada', question: questionEl.value};
        sendMessage({type:'init', payload: initPayload});
        stateEl.textContent = 'Pregunta cargada';
        
        // Mantén referencia de respuestas en la ronda actual para acciones como "finalizar ronda"
        currentRound.answers = answers.map(a=>({...a}));
        
        console.log('✅ Pregunta cargada:', payload.question);
    }catch(e){
        console.error('Error loading selected question', e);
        alert('Ocurrió un error al cargar la pregunta');
    }
}

// Modal functions removed - now using simple dropdown
function showGameQuestionSelector_REMOVED(gameId, questions) {
    const gameName = gameId === 'unassigned' ? 'Preguntas Sin Asignar' : `Partida ${Object.keys(dropdownQuestionsIndex).indexOf(gameId) + 1}`;
    
    const roundQuestions = questions.filter(q => q.question_type === 'round');
    const fastMoneyQuestions = questions.filter(q => q.question_type === 'fast_money');
    
    let questionsHTML = '';
    
    // Render round questions
    if (roundQuestions.length > 0) {
        questionsHTML += `
            <div style="margin-bottom: 20px;">
                <h4 style="color: var(--accent2); margin: 0 0 10px 0; font-size: 16px;">🎯 Preguntas de Ronda (${roundQuestions.length})</h4>
                <div style="display: grid; gap: 8px;">
        `;
        
        roundQuestions.forEach(q => {
            const cat = q.category === 'dev' ? 'Desarrollo' : 'General';
            const usedCount = q.times_used || 0;
            questionsHTML += `
                <div class="question-item" onclick="selectQuestion(${q.id})" style="
                    background: rgba(69,162,158,0.1); 
                    border: 2px solid rgba(69,162,158,0.3); 
                    border-radius: 6px; 
                    padding: 12px; 
                    cursor: pointer; 
                    transition: all 0.2s;
                " onmouseover="this.style.borderColor='rgba(69,162,158,0.6)'; this.style.background='rgba(69,162,158,0.2)'" onmouseout="this.style.borderColor='rgba(69,162,158,0.3)'; this.style.background='rgba(69,162,158,0.1)'">
                    <div style="font-weight: 600; color: var(--accent2); margin-bottom: 4px;">${q.name}</div>
                    <div style="font-size: 12px; color: var(--muted);">
                        <span style="background: rgba(102,252,241,0.2); padding: 2px 6px; border-radius: 3px; margin-right: 8px;">${cat}</span>
                        <span>Usada ${usedCount} veces</span>
                    </div>
                </div>
            `;
        });
        
        questionsHTML += '</div></div>';
    }
    
    // Render fast money questions
    if (fastMoneyQuestions.length > 0) {
        questionsHTML += `
            <div style="margin-bottom: 20px;">
                <h4 style="color: #ffc107; margin: 0 0 10px 0; font-size: 16px;">⚡ Dinero Rápido (${fastMoneyQuestions.length})</h4>
                <div style="display: grid; gap: 8px;">
        `;
        
        fastMoneyQuestions.forEach(q => {
            const cat = q.category === 'dev' ? 'Desarrollo' : 'General';
            const usedCount = q.times_used || 0;
            questionsHTML += `
                <div class="question-item" onclick="selectQuestion(${q.id})" style="
                    background: rgba(255,193,7,0.1); 
                    border: 2px solid rgba(255,193,7,0.3); 
                    border-radius: 6px; 
                    padding: 12px; 
                    cursor: pointer; 
                    transition: all 0.2s;
                " onmouseover="this.style.borderColor='rgba(255,193,7,0.6)'; this.style.background='rgba(255,193,7,0.2)'" onmouseout="this.style.borderColor='rgba(255,193,7,0.3)'; this.style.background='rgba(255,193,7,0.1)'">
                    <div style="font-weight: 600; color: #ffc107; margin-bottom: 4px;">${q.name}</div>
                    <div style="font-size: 12px; color: var(--muted);">
                        <span style="background: rgba(102,252,241,0.2); padding: 2px 6px; border-radius: 3px; margin-right: 8px;">${cat}</span>
                        <span>Usada ${usedCount} veces</span>
                    </div>
                </div>
            `;
        });
        
        questionsHTML += '</div></div>';
    }
    
    if (questionsHTML === '') {
        questionsHTML = '<p style="color: var(--muted); text-align: center; padding: 20px;">No hay preguntas en esta partida</p>';
    }
    
    const modalHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; display: flex; align-items: center; justify-content: center; overflow-y: auto;" id="gameQuestionModal">
            <div style="background: var(--bg); border: 2px solid var(--accent); border-radius: 8px; padding: 20px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto; margin: 20px;">
                <div style="position: sticky; top: 0; background: var(--bg); padding: 0 0 15px 0; border-bottom: 1px solid rgba(102,252,241,0.2); margin-bottom: 20px;">
                    <h3 style="color: var(--accent); margin: 0 0 10px 0;">🎮 ${gameName}</h3>
                    <p style="color: var(--text); margin: 0; font-size: 14px;">Haz clic en cualquier pregunta para cargarla al controller:</p>
                </div>
                <div id="questionsContainer">
                    ${questionsHTML}
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(102,252,241,0.2);">
                    <button onclick="closeGameQuestionModal()" style="background: var(--muted); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600;">Cancelar</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Modal function removed

// Select a question from the visual selector
async function selectQuestion(questionId) {
    try {
        const res = await fetch(`${API_QUESTIONS_BASE}/${questionId}/load`);
        const payload = await res.json();
        if (!payload || !payload.success) {
            alert('No se pudo cargar la pregunta');
            return;
        }
        
        questionEl.value = payload.question || '';
        answers = (payload.answers || []).map(a => ({text: a.text, count: a.count, revealed: false, correct: false}));
        answers.sort((a, b) => b.count - a.count);
        render();
        
        const initPayload = {answers, state: 'Pregunta cargada', question: questionEl.value};
        sendMessage({type: 'init', payload: initPayload});
        stateEl.textContent = 'Pregunta cargada';
        
        // Keep reference of answers in current round for actions like "finish round"
        currentRound.answers = answers.map(a => ({...a}));
        
    } catch (e) {
        console.error('Error loading selected question', e);
        alert('Ocurrió un error al cargar la pregunta');
    }
}

// Partida tracking functions removed - simplified to individual questions

// Auto-advance function removed - simplified to individual questions

// Legacy function for dropdown selector (kept for compatibility)
async function loadGameQuestion() {
    const select = document.getElementById('gameQuestionSelect');
    if (!select) return;
    
    const questionId = select.value;
    if (!questionId) {
        alert('Selecciona una pregunta');
        return;
    }
    
    selectQuestion(questionId);
}

if(loadSelectedBtn){ loadSelectedBtn.addEventListener('click', loadSelectedQuestion); }
if(refreshQuestionsBtn){ refreshQuestionsBtn.addEventListener('click', fetchQuestionsForDropdown); }
if(questionDropdownEl){ questionDropdownEl.addEventListener('change', (e)=>{ if(e.target.value) loadSelectedQuestion(); }); }

// initial population on load
fetchQuestionsForDropdown();


 

let answers = [];
let teamScores = {}; // persisted per browser
let currentRound = {points:0, teams:[], accumulatedPoints:0};
let strikeCount = 0; // Counter for X's (wrong answers)
let autoResendTimer = null;
let activeTeam = null; // current team answering
let roundNumber = 1; // Track which round we're on
let pointMultiplier = 1; // Points multiplier (1x, 2x, 3x)
let isRoundActive = false; // Track if there's an active round to lock team name inputs

// Partida variables removed - simplified to individual questions

// Function to lock/unlock team name inputs based on round state
function setTeamInputsLocked(locked) {
    const team1Input = document.getElementById('team1Input');
    const team2Input = document.getElementById('team2Input');
    if(team1Input) {
        team1Input.disabled = locked;
        team1Input.style.opacity = locked ? '0.6' : '1';
        team1Input.style.cursor = locked ? 'not-allowed' : 'text';
        team1Input.title = locked ? 'No se pueden cambiar los nombres durante una ronda activa' : '';
    }
    if(team2Input) {
        team2Input.disabled = locked;
        team2Input.style.opacity = locked ? '0.6' : '1';
        team2Input.style.cursor = locked ? 'not-allowed' : 'text';
        team2Input.title = locked ? 'No se pueden cambiar los nombres durante una ronda activa' : '';
    }
}

// auto-resend when answers change, to help boards that miss initial message
function scheduleAutoResend(){ 
    if(autoResendTimer) clearTimeout(autoResendTimer); 
    autoResendTimer = setTimeout(()=>{ 
        const payload = {answers, state:stateEl.textContent, question:questionEl.value}; 
        sendMessage({type:'init', payload}); 
    }, 800); 
}

function render() {
    answersEl.innerHTML = '';
    answers.forEach((a, i) => {
        const item = document.createElement('div');
        item.className = 'answer' + (a.correct ? ' answer-correct' : '');
        item.innerHTML = `
                <div style="flex:1">
                    <strong>${i+1}. </strong>
                    <input type="text" data-idx="${i}" value="${a.text}" style="width:70%" readonly />
                    <input type="number" data-idx-count="${i}" value="${a.count}" style="width:60px;margin-left:8px" readonly />
                </div>
                <div>
                    
                    <button data-action="correct" data-idx="${i}" title="Marcar como acertada" ${a.correct ? 'disabled' : ''}>Acierto</button>
                    <!--<button data-action="hide" data-idx="${i}">Ocultar</button>-->
                    <!--<button data-action="remove" data-idx="${i}">Eliminar</button>-->
                </div>
            `;
        answersEl.appendChild(item);
    });
        scheduleAutoResend();
}

answersEl.addEventListener('click', (ev) => {
    const btn = ev.target.closest('button');
    if (!btn) return;
    const action = btn.getAttribute('data-action');
    const idx = Number(btn.getAttribute('data-idx'));
    if (action === 'reveal') {
        showTerminalMessage(`reveal --answer=${idx+1} --text="${answers[idx]?.text || 'N/A'}" ✓`);
        sendMessage({type:'reveal', payload:{index:idx}});
        answers[idx].revealed = true;
        showAssignIfRoundComplete();
    } else if (action === 'correct') {
        // prevent double scoring: if already marked correct, ignore
        if(answers[idx] && answers[idx].correct) { return; }
        
        const pts = Number(answers[idx] && answers[idx].count) || 0;
        
        // Play success sound
        playSuccessSound();
        
        // reveal on board
        sendMessage({type:'reveal', payload:{index:idx}});
        answers[idx].revealed = true;
        answers[idx].correct = true;
        
        // Add points to ROUND total (not to team yet!)
        if(!currentRound.accumulatedPoints) currentRound.accumulatedPoints = 0;
        currentRound.accumulatedPoints += pts;
        
        // Update round points display (with multiplier)
        sendMessage({type:'update_round_total', payload:{points: currentRound.accumulatedPoints, multiplier: pointMultiplier}});
        
        // Reset timer on correct answer (but don't start it)
        if(timerRunning || timerRemaining < timerInitialTime){
            resetTimer();
        }
        
        render();
        showAssignIfRoundComplete();
    } else if (action === 'hide') {
        answers[idx].revealed = false;
        sendMessage({type:'init', payload:{answers, state:stateEl.textContent}});
    } else if (action === 'remove') {
        answers.splice(idx,1);
        sendMessage({type:'init', payload:{answers, state:stateEl.textContent}});
    }
        render();
        scheduleAutoResend();
});

answersEl.addEventListener('input', (ev) => {
    const input = ev.target;
    if (input.hasAttribute('data-idx')) {
        const i = Number(input.getAttribute('data-idx'));
        answers[i].text = input.value;
    }
    if (input.hasAttribute('data-idx-count')) {
        const i = Number(input.getAttribute('data-idx-count'));
        answers[i].count = Number(input.value) || 0;
    }
        scheduleAutoResend();
});

// document.getElementById('setDefaults').addEventListener('click', () => {
//     questionEl.value = '¿Qué fruta comen más los niños?';
//     answers = [
//         {text:'Manzana', count:32, revealed:false},
//         {text:'Plátano', count:25, revealed:false},
//         {text:'Naranja', count:18, revealed:false},
//         {text:'Fresa', count:12, revealed:false},
//         {text:'Uva', count:8, revealed:false}
//     ];
//     render();
// });

// document.getElementById('addAnswer').addEventListener('click', ()=>{
//     answers.push({text:'Nueva respuesta', count:0, revealed:false});
//     render();
// });

// round controls
const team1Input = document.getElementById('team1Input');
const team2Input = document.getElementById('team2Input');
const teamsValidation = document.getElementById('teamsValidation');
const startRoundBtn = document.getElementById('startRound');
const nextRoundBtn = document.getElementById('nextRound');
const finishRoundBtn = document.getElementById('finishRound');
const roundAssignEl = document.getElementById('roundAssign');
const roundTeamButtons = document.getElementById('roundTeamButtons');
const turnControls = document.getElementById('turnControls');
const activeTeamButtons = document.getElementById('activeTeamButtons');
const stealAttemptEl = document.getElementById('stealAttempt');
const stealTeamNameEl = document.getElementById('stealTeamName');
const stealSuccessBtn = document.getElementById('stealSuccess');
const stealFailBtn = document.getElementById('stealFail');
const multiplier1Btn = document.getElementById('multiplier1');
const multiplier2Btn = document.getElementById('multiplier2');
const multiplier3Btn = document.getElementById('multiplier3');

// Helper function to get team names from inputs
function getTeamNames() {
    const name1 = (team1Input.value || '').trim();
    const name2 = (team2Input.value || '').trim();
    return name1 && name2 ? [name1, name2] : [];
}

// live validation for teams
function validateTeams() {
    const teamNames = getTeamNames();
    const valid = teamNames.length === 2;
    if(teamsValidation) {
        teamsValidation.style.display = valid ? 'none' : 'inline-block';
    }
    // Enable/disable 'Siguiente ronda' based on whether input matches current teams
    const currentTeams = (currentRound && Array.isArray(currentRound.teams)) ? currentRound.teams : [];
    const teamsMatch = teamNames.length === 2 && currentTeams.length === 2 && 
                       teamNames.every(t => currentTeams.includes(t)) && 
                       currentTeams.every(t => teamNames.includes(t));
    if(nextRoundBtn) nextRoundBtn.disabled = !teamsMatch;
    
    // Update team input lock state based on round activity
    setTeamInputsLocked(isRoundActive);
}

team1Input.addEventListener('input', ()=>{
    validateTeams();
    broadcastTeamNamesIfReady();
});
team2Input.addEventListener('input', ()=>{
    validateTeams();
    broadcastTeamNamesIfReady();
});

function broadcastTeamNamesIfReady(){
    // No permitir cambios de nombres si hay una ronda activa
    if(isRoundActive) {
        console.log('⚠️ No se pueden cambiar nombres de equipos durante una ronda activa');
        return;
    }
    
    const names = getTeamNames();
    if(names.length === 2){
        // Remap local controller scores by position to preserve points
        const existingOrder = (currentRound && Array.isArray(currentRound.teams) && currentRound.teams.length===2)
            ? currentRound.teams.slice()
            : Object.keys(teamScores);
        if(existingOrder.length === 2){
            const old0 = existingOrder[0];
            const old1 = existingOrder[1];
            const new0 = names[0];
            const new1 = names[1];
            const newScores = {};
            newScores[new0] = Number(teamScores[old0]||0);
            newScores[new1] = Number(teamScores[old1]||0);
            teamScores = newScores;
            currentRound.teams = names.slice();
            persistTeamScores();
            renderTeamScores();
        }
        // Inform board
        sendMessage({type:'team_names', payload:{teams: names}});
    }
}

// Multiplier button handlers
    [multiplier1Btn, multiplier2Btn, multiplier3Btn].forEach(btn => {
    if(btn) {
        btn.addEventListener('click', () => {
            // Remove active from all
            document.querySelectorAll('.multiplier-btn').forEach(b => b.classList.remove('active'));
            // Add active to clicked
            btn.classList.add('active');
            // Update multiplier value
            pointMultiplier = parseInt(btn.dataset.multiplier);
            console.log('🎯 Multiplicador cambiado a:', pointMultiplier + 'x');
            // Broadcast live multiplier change so the Board updates its banner immediately
            try{
                sendMessage({ type:'multiplier', payload:{ multiplier: pointMultiplier } });
            }catch(e){ console.debug('No se pudo enviar cambio de multiplicador', e); }

            // If the assignment panel is visible, update the text with the new multiplier
            try{
                if(roundAssignEl && roundAssignEl.style.display !== 'none' && currentRound){
                    const base = Number(currentRound.accumulatedPoints || 0);
                    const finalPts = base * pointMultiplier;
                    const readyText = document.getElementById('roundReadyText');
                    const multTxt = pointMultiplier > 1 ? ` (x${pointMultiplier})` : '';
                    if(readyText) readyText.textContent = `La ronda terminó. Asignar ${finalPts} puntos${multTxt} a:`;
                }
            }catch(e){ console.debug('No se pudo actualizar el texto de asignación', e); }
        });
    }
});

startRoundBtn.addEventListener('click', ()=>{
    const teamNames = getTeamNames();
    if(teamNames.length !== 2){
        showTerminalMessage('error --missing-teams --required=2 ❌');
        if(teamsValidation){ 
            teamsValidation.textContent = 'Ingresa el nombre de ambos equipos'; 
            teamsValidation.style.display = 'inline-block'; 
        }
        return;
    } else {
        if(teamsValidation){ teamsValidation.style.display = 'none'; }
    }
    // New game round: reset scoreboard for these 2 teams
    roundNumber = 1;
    showTerminalMessage(`round --start=1 --teams="${teamNames.join('","')}" 🚀`);
    runRound(teamNames, /*keepScores*/ false);
});

// Start next round keeping existing scores and same teams (if available)
nextRoundBtn.addEventListener('click', ()=>{
    let teamNames = [];
    if(currentRound && Array.isArray(currentRound.teams) && currentRound.teams.length === 2){
        teamNames = currentRound.teams.slice(0,2);
    } else {
        teamNames = getTeamNames();
    }
    if(teamNames.length !== 2){
        if(teamsValidation){ 
            teamsValidation.textContent = 'Ingresa el nombre de ambos equipos'; 
            teamsValidation.style.display = 'inline-block'; 
        }
        return;
    }
    roundNumber++;
    showTerminalMessage(`round --next=${roundNumber} --keep-scores 🎯`);
    runRound(teamNames, /*keepScores*/ true);
});

// Finish round - reveal all unrevealed answers without adding points
finishRoundBtn.addEventListener('click', ()=>{
    // Necesitamos una pregunta cargada (answers) para poder revelar
    if(!answers || !Array.isArray(answers) || answers.length === 0){
        alert('⚠️ No hay pregunta cargada');
        return;
    }

    // Hide steal attempt UI if active
    if(stealAttemptEl && stealAttemptEl.style.display !== 'none') {
        console.log('⚠️ Cancelando robo de puntos activo');
        stealAttemptEl.style.display = 'none';
    }
    
    // Indices de respuestas no reveladas (según la lista actual)
    const unrevealedIdx = answers
        .map((a,idx)=> (!a.revealed ? idx : -1))
        .filter(i=> i !== -1);
    if(unrevealedIdx.length === 0) {
        alert('⚠️ Todas las respuestas ya están reveladas');
        return;
    }
    
    console.log('🏁 Finalizando ronda - revelando', unrevealedIdx.length, 'respuestas con delay');

    // 🎭 Agregar efecto teatral al botón de finalizar
    finishRoundBtn.classList.add('curtain-effect');
    finishRoundBtn.textContent = '🎭 Telón en marcha...';
    finishRoundBtn.disabled = true;

    // 🎭 Enviar señal al board para mostrar telón de finalización
    sendMessage({type:'round_finish_curtain', payload:{unrevealedCount: unrevealedIdx.length}});

    // Revelar una por una con delay sin sumar puntos (delay mayor para sincronizar con telón)
    unrevealedIdx.forEach((idx, order)=>{
        setTimeout(()=>{
            if(answers[idx]){
                answers[idx].revealed = true;
                // Mantener mirror en currentRound.answers si existe
                if(currentRound && Array.isArray(currentRound.answers) && currentRound.answers[idx]){
                    currentRound.answers[idx].revealed = true;
                }
                // Enviar al board la revelación individual
                sendMessage({type:'reveal', payload:{index: idx}});
                render();
            }
        }, 3000 + (order * 600)); // 3 segundos para el telón + 600ms entre revelados
    });

    // Log al finalizar toda la secuencia y restaurar botón
    setTimeout(()=>{
        console.log('✅ Todas las respuestas reveladas. Puntos acumulados:', currentRound.accumulatedPoints || 0);
        
        // 🎭 Restaurar botón de finalizar
        finishRoundBtn.classList.remove('curtain-effect');
        finishRoundBtn.textContent = '🏁 Finalizar ronda';
        finishRoundBtn.disabled = false;
        
    }, 3000 + (unrevealedIdx.length * 600) + 100);
});

// Common routine to transition to a new round
function runRound(teamNames, keepScores){
    // disable buttons during transition
    startRoundBtn.disabled = true;
    nextRoundBtn.disabled = true;
    
    // 🎭 Agregar efecto teatral si no es la primera ronda
    if(roundNumber > 1) {
        nextRoundBtn.classList.add('curtain-effect');
        startRoundBtn.classList.add('curtain-effect');
        console.log('🎭 Preparando transición teatral para Ronda', roundNumber);
    }
    
    // Mostrar estado de preparación
    startRoundBtn.textContent = '🎭 Preparando ronda...';
    
    // Ejecutar transición después de un breve delay (para que se vea el cambio de botón)
    setTimeout(() => {
        // 🎭 Quitar efectos teatrales de los botones
        nextRoundBtn.classList.remove('curtain-effect');
        startRoundBtn.classList.remove('curtain-effect');
        
        // Restaurar botones
        startRoundBtn.textContent = 'Iniciar ronda';
        startRoundBtn.disabled = false;
        nextRoundBtn.disabled = false;
        
        // clear answers but keep team scores
        answers = [];
        questionEl.value = '';
        render();
        
        // reset strike count
        strikeCount = 0;
        updateStrikeDisplay();
        sendMessage({type:'update_strikes', payload:{count: strikeCount}});
        
        // send empty init to board to clear questions
        sendMessage({type:'init', payload:{answers:[], state:'Nueva ronda', question:''}});
        
        // Auto-ajustar multiplicador por número de ronda (R1=x1, R2=x2, R3+=x3)
        let desiredMultiplier = 1;
        if(roundNumber >= 3) desiredMultiplier = 3; else if(roundNumber === 2) desiredMultiplier = 2;
        if(pointMultiplier !== desiredMultiplier){
            pointMultiplier = desiredMultiplier;
            // actualizar UI de botones
            document.querySelectorAll('.multiplier-btn').forEach(b => b.classList.remove('active'));
            const btn = document.getElementById('multiplier'+pointMultiplier);
            if(btn) btn.classList.add('active');
            // notificar al board de inmediato
            sendMessage({type:'multiplier', payload:{multiplier: pointMultiplier}});
        }

        // announce teams and reset round points to 0 (round points accumulate automáticamente en aciertos)
        // Enviamos keepScores para que el tablero sepa si debe conservar o reiniciar marcadores
        sendMessage({type:'round_points', payload:{points:0, teams: teamNames, roundNumber: roundNumber, multiplier: pointMultiplier, keepScores: !!keepScores}});
        // Enviar también team_names para asegurar mapeo de nombres->puntos por lado
        sendMessage({type:'team_names', payload:{teams: teamNames, points: 0}});
        // Make sure controller local state also knows the teams immediately (works even if storage fallback)
        currentRound.points = 0;
        currentRound.accumulatedPoints = 0;
        currentRound.teams = teamNames.slice();
        currentRound.roundNumber = roundNumber;
        currentRound.multiplier = pointMultiplier;

        // reset active team UI and board highlight
        activeTeam = null;
        renderActiveTeamButtons(teamNames);
        turnControls.style.display = 'block';
        sendMessage({type:'active_team', payload:{team:null}});
        
        // hide assignment area until board signals round_ready
        roundAssignEl.style.display = 'none';
        
        // Score handling
        if(keepScores){
            // keep existing totals; just ensure keys exist
            teamNames.forEach(t=>{ if(!(t in teamScores)) teamScores[t] = 0; });
            // also remove any extra teams beyond these two
            Object.keys(teamScores).forEach(k=>{ if(!teamNames.includes(k)) delete teamScores[k]; });
        } else {
            // reset to 0 for a fresh game with these two
            teamScores = {};
            teamNames.forEach(t=>{ teamScores[t] = 0; });
        }
        persistTeamScores();
        renderTeamScores();
        
        // Update round number display
        const roundNumEl = document.getElementById('roundNumber');
        if(roundNumEl) roundNumEl.textContent = `Ronda: ${roundNumber}`;
        
        // Enable Fast Money after completing round 3 (when starting round 4)
        if(roundNumber >= 4) {
            unlockFastMoney();
            showTerminalMessage(`fast-money --unlocked --round=${roundNumber} 🏆`);
        }
        
        // 🔒 Activar bloqueo de equipos - ronda en curso
        isRoundActive = true;
        setTeamInputsLocked(true);
        console.log('🔒 Nombres de equipos bloqueados - ronda activa');
        
        stateEl.textContent = 'Nueva ronda';
    }, 800); // Breve delay para la transición suave
}


function showAssignIfRoundComplete(){
    const realAnswers = answers.filter(a=>a && a.text);
    const allRevealed = realAnswers.length>0 && realAnswers.every(a=>a.revealed);
    if(allRevealed && currentRound){
        const basePoints = Number(currentRound.accumulatedPoints || 0);
        const finalPoints = basePoints * pointMultiplier; // Apply multiplier!
        if(finalPoints <= 0) return;

        console.log(`💰 Asignando ${basePoints} x${pointMultiplier} = ${finalPoints} puntos`);

        if(activeTeam){
            // Auto-assign to selected active team
            sendMessage({type:'assign_points', payload:{team:activeTeam, points: finalPoints, roundNumber: roundNumber}});
            if(!(activeTeam in teamScores)) teamScores[activeTeam]=0;
            teamScores[activeTeam] = Number(teamScores[activeTeam]||0) + finalPoints;
            persistTeamScores();
            renderTeamScores();
            roundAssignEl.style.display = 'none';
            currentRound = {points:0, teams:[], accumulatedPoints:0};
        } else {
            // Fallback: manual choose winner if no active team selected
            roundTeamButtons.innerHTML = '';
            const readyText = document.getElementById('roundReadyText');
            const multiplierText = pointMultiplier > 1 ? ` (x${pointMultiplier})` : '';
            if(readyText) readyText.textContent = `La ronda terminó. Asignar ${finalPoints} puntos${multiplierText} a:`;
            (currentRound.teams || []).forEach(t=>{
                const b = document.createElement('button'); b.textContent = t; b.addEventListener('click', ()=>{
                    sendMessage({type:'assign_points', payload:{team:t, points: finalPoints, roundNumber: roundNumber}});
                    if(!(t in teamScores)) teamScores[t]=0;
                    teamScores[t] = Number(teamScores[t]||0) + finalPoints;
                    persistTeamScores();
                    renderTeamScores();
                    roundAssignEl.style.display = 'none';
                    currentRound = {points:0, teams:[], accumulatedPoints:0};
                });
                roundTeamButtons.appendChild(b);
            });
            roundAssignEl.style.display = 'block';
        }
    }
}

// Active team selection buttons
function renderActiveTeamButtons(names){
    if(!activeTeamButtons) return;
    activeTeamButtons.innerHTML = '';
    (names || []).forEach(name=>{
        const b = document.createElement('button');
        b.className = 'active-team-btn' + (activeTeam === name ? ' selected' : '');
        b.textContent = name;
        b.addEventListener('click', ()=> selectActiveTeam(name));
        activeTeamButtons.appendChild(b);
    });
}

function selectActiveTeam(name){
    activeTeam = name;
    currentRound.activeTeam = name;
    // Update buttons selection
    Array.from(activeTeamButtons.children).forEach(child=>{
        child.classList.toggle('selected', child.textContent === name);
    });
    // Inform board to highlight
    sendMessage({type:'active_team', payload:{team:name}});
    
    // Show timer when team is selected
    const timerContainer = document.getElementById('timerContainer');
    if(timerContainer) timerContainer.style.display = 'block';
}

// ===== TIMER DE RESPUESTA =====
let timerInterval = null;
let timerRemaining = 30;
let timerRunning = false;
let timerInitialTime = 30;

const timerDisplay = document.getElementById('timerDisplay');
const timerProgressBar = document.getElementById('timerProgressBar');
const timerSecondsInput = document.getElementById('timerSeconds');
const startTimerBtn = document.getElementById('startTimer');
const pauseTimerBtn = document.getElementById('pauseTimer');
const resetTimerBtn = document.getElementById('resetTimer');

function updateTimerDisplay(){
    if(!timerDisplay) return;
    timerDisplay.textContent = timerRemaining;
    
    // Update progress bar
    if(timerProgressBar){
        const percent = (timerRemaining / timerInitialTime) * 100;
        timerProgressBar.style.width = percent + '%';
        
        // Color based on time remaining
        if(percent <= 25){
            timerProgressBar.style.background = 'linear-gradient(90deg, #ef4444, #dc2626)';
        } else if(percent <= 50){
            timerProgressBar.style.background = 'linear-gradient(90deg, #f59e0b, #d97706)';
        } else {
            timerProgressBar.style.background = 'linear-gradient(90deg, var(--accent), var(--accent2))';
        }
    }
    
    // Alert sound when exactly 5 seconds remaining
    if(timerRemaining === 5 && timerRunning){
        playFiveSecondAlert();
    }
    
    // Pulse effect when low time
    if(timerRemaining <= 5 && timerRemaining > 0 && timerRunning){
        timerDisplay.style.animation = 'pulse 0.5s ease-in-out';
        timerDisplay.style.color = '#ef4444';
        // Play tick sound
        playTickSound();
    } else {
        timerDisplay.style.animation = 'none';
        timerDisplay.style.color = 'var(--accent)';
    }
}

function playTickSound(){
    try{
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }catch(e){}
}

function playFiveSecondAlert(){
    try{
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        // Double beep - distinctive alert sound
        // First beep - higher pitch
        const osc1 = audioContext.createOscillator();
        const gain1 = audioContext.createGain();
        osc1.connect(gain1);
        gain1.connect(audioContext.destination);
        osc1.frequency.value = 1400; // High pitch
        osc1.type = 'sine';
        gain1.gain.setValueAtTime(0.25, audioContext.currentTime);
        gain1.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        osc1.start(audioContext.currentTime);
        osc1.stop(audioContext.currentTime + 0.2);
        
        // Second beep - same pitch, slightly delayed
        const osc2 = audioContext.createOscillator();
        const gain2 = audioContext.createGain();
        osc2.connect(gain2);
        gain2.connect(audioContext.destination);
        osc2.frequency.value = 1400;
        osc2.type = 'sine';
        gain2.gain.setValueAtTime(0.25, audioContext.currentTime + 0.25);
        gain2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.45);
        osc2.start(audioContext.currentTime + 0.25);
        osc2.stop(audioContext.currentTime + 0.45);
        
        console.log('🔔 ¡Alerta de 5 segundos!');
    }catch(e){
        console.error('Error playing five second alert:', e);
    }
}

function playAlarmSound(){
    try{
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        // Triple beep alarm
        [0, 0.15, 0.3].forEach(delay => {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            oscillator.frequency.value = 1200;
            oscillator.type = 'square';
            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime + delay);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + delay + 0.12);
            oscillator.start(audioContext.currentTime + delay);
            oscillator.stop(audioContext.currentTime + delay + 0.12);
        });
    }catch(e){}
}

// Success sound - triumphant ascending tones
function playSuccessSound(){
    try{
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        // Three ascending notes - like a "ding ding ding!"
        const frequencies = [523.25, 659.25, 783.99]; // C5, E5, G5 (major chord)
        frequencies.forEach((freq, index) => {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            oscillator.frequency.value = freq;
            oscillator.type = 'sine';
            const startTime = audioContext.currentTime + (index * 0.1);
            gainNode.gain.setValueAtTime(0.3, startTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, startTime + 0.3);
            oscillator.start(startTime);
            oscillator.stop(startTime + 0.3);
        });
    }catch(e){}
}

// Error sound - descending buzz
function playErrorSound(){
    try{
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        // Buzzer sound - harsh and descending
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.frequency.setValueAtTime(300, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(100, audioContext.currentTime + 0.3);
        oscillator.type = 'sawtooth';
        gainNode.gain.setValueAtTime(0.25, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
    }catch(e){}
}

function startTimer(){
    if(timerRunning) return;
    
    timerRunning = true;
    if(startTimerBtn) startTimerBtn.style.display = 'none';
    if(pauseTimerBtn) pauseTimerBtn.style.display = 'inline-block';
    
    timerInterval = setInterval(() => {
        timerRemaining--;
        updateTimerDisplay();
        
        if(timerRemaining <= 0){
            stopTimer();
            onTimerExpired();
        }
    }, 1000);
}

function pauseTimer(){
    if(!timerRunning) return;
    
    timerRunning = false;
    if(timerInterval) clearInterval(timerInterval);
    if(startTimerBtn) startTimerBtn.style.display = 'inline-block';
    if(pauseTimerBtn) pauseTimerBtn.style.display = 'none';
}

function stopTimer(){
    timerRunning = false;
    if(timerInterval) clearInterval(timerInterval);
    if(startTimerBtn) startTimerBtn.style.display = 'inline-block';
    if(pauseTimerBtn) pauseTimerBtn.style.display = 'none';
}

function resetTimer(){
    stopTimer();
    timerInitialTime = parseInt(timerSecondsInput?.value || 10);
    timerRemaining = timerInitialTime;
    updateTimerDisplay();
}

function onTimerExpired(){
    playAlarmSound();
    
    // Flash the display
    if(timerDisplay){
        timerDisplay.style.animation = 'pulse 0.3s ease-in-out 3';
        timerDisplay.style.color = '#ef4444';
    }
    
    // Auto-add strike (X)
    setTimeout(() => {
        const addStrikeBtn = document.getElementById('addStrike');
        if(addStrikeBtn) addStrikeBtn.click();
        alert('⏰ ¡Tiempo agotado! Se agregó una X automáticamente.');
        
        // Auto-reset for next turn
        resetTimer();
    }, 500);
}

// Timer button handlers
if(startTimerBtn) startTimerBtn.addEventListener('click', startTimer);
if(pauseTimerBtn) pauseTimerBtn.addEventListener('click', pauseTimer);
if(resetTimerBtn) resetTimerBtn.addEventListener('click', resetTimer);
if(timerSecondsInput){
    timerSecondsInput.addEventListener('change', () => {
        if(!timerRunning){
            resetTimer();
        }
    });
}

// Initialize timer
updateTimerDisplay();


document.getElementById('sendInit').addEventListener('click', () => {
    const payload = {answers, state:'En juego', question: questionEl.value};
    console.debug('[controller] sending init', payload);
    showTerminalMessage('init --send-to-board --answers=' + answers.length + ' 📤');
    sendMessage({type:'init', payload});
    stateEl.textContent = 'En juego';
});
 

document.getElementById('resend').addEventListener('click', ()=>{
    const payload = {answers, state:stateEl.textContent, question: questionEl.value};
    console.debug('[controller] manual resend', payload);
    sendMessage({type:'init', payload});
});

document.getElementById('testConnection').addEventListener('click', ()=>{
    const testMsg = {
        type: 'test_connection',
        timestamp: Date.now(),
        payload: { message: 'Test de conexión desde controller' }
    };
    console.log('[controller] Enviando test de conexión:', testMsg);
    sendMessage(testMsg);
    
    // Visual feedback
    const btn = document.getElementById('testConnection');
    const originalText = btn.textContent;
    btn.textContent = '🔗 Enviando...';
    btn.style.background = '#f59e0b';
    setTimeout(() => {
        btn.textContent = originalText;
        btn.style.background = '#10b981';
    }, 1000);
});

document.getElementById('reset').addEventListener('click', () => {
    // Reset everything to 0
    answers = [];
    questionEl.value = '';
    strikeCount = 0;
    currentRound = {points:0, teams:[], accumulatedPoints:0};
    activeTeam = null;
    teamScores = {};
    roundNumber = 1;
    
    // 🔓 Desbloquear nombres de equipos - reset general
    isRoundActive = false;
    setTeamInputsLocked(false);
    
    // Clear localStorage first
    localStorage.removeItem('game-team-scores');
    
    // Update displays
    updateStrikeDisplay();
    renderTeamScores();
    render();
    const roundNumEl = document.getElementById('roundNumber');
    if(roundNumEl) roundNumEl.textContent = 'Ronda: 1';
    
    // Reset and hide timer
    stopTimer();
    resetTimer();
    const timerContainer = document.getElementById('timerContainer');
    if(timerContainer) timerContainer.style.display = 'none';
    
    // Send comprehensive reset to board
    console.log('[controller] ===== RESET CLICKED =====');
    console.log('[controller] usingBroadcast:', usingBroadcast);
    console.log('[controller] channel:', channel);
    console.log('[controller] Enviando reset_all');
    const resetMsg = {type:'reset_all', payload:{}};
    console.log('[controller] Mensaje a enviar:', resetMsg);
    sendMessage(resetMsg);
    console.log('[controller] Mensaje enviado');
    
    stateEl.textContent = 'Listo';
    
    // Hide assignment area
    const roundAssignEl = document.getElementById('roundAssign');
    if(roundAssignEl) roundAssignEl.style.display = 'none';
    if(turnControls) turnControls.style.display = 'none';
    if(stealAttemptEl) stealAttemptEl.style.display = 'none';
    // Clear active team highlight on board
    sendMessage({type:'active_team', payload:{team:null}});
});

// Nueva partida: reinicia nombres, puntos, ronda y UI; bloquea siguiente ronda hasta definir equipos
const newGameBtn = document.getElementById('newGame');
if(newGameBtn){
    newGameBtn.addEventListener('click', ()=>{
        showTerminalMessage('game --new-match --reset-all 🎮');
        
        // Limpiar inputs de equipos (nueva partida empieza sin nombres)
        if(team1Input) team1Input.value = '';
        if(team2Input) team2Input.value = '';

        // Reset de estado local
        answers = [];
        if(questionEl) questionEl.value = '';
        strikeCount = 0;
        updateStrikeDisplay();
        currentRound = {points:0, teams:[], accumulatedPoints:0};
        activeTeam = null;
        roundNumber = 1;
        pointMultiplier = 1;
        teamScores = {};
        persistTeamScores();
        renderTeamScores();
        render();

        // UI: ronda y controles
        const roundNumEl = document.getElementById('roundNumber');
        if(roundNumEl) roundNumEl.textContent = 'Ronda: 1';
        if(turnControls) turnControls.style.display = 'none';
        if(roundAssignEl) roundAssignEl.style.display = 'none';
        if(stealAttemptEl) stealAttemptEl.style.display = 'none';

        // Reset and hide timer
        stopTimer();
        resetTimer();
        const timerContainer = document.getElementById('timerContainer');
        if(timerContainer) timerContainer.style.display = 'none';

        // Restablecer botones de multiplicador a x1
        document.querySelectorAll('.multiplier-btn').forEach(b => b.classList.remove('active'));
        const mult1 = document.getElementById('multiplier1');
        if(mult1) mult1.classList.add('active');

        // Deshabilitar "Siguiente ronda" hasta que se definan los mismos equipos
        if(nextRoundBtn) nextRoundBtn.disabled = true;
        
        // 🔓 Desbloquear nombres de equipos - nueva partida
        isRoundActive = false;
        setTeamInputsLocked(false);
        console.log('🔓 Nombres de equipos desbloqueados - nueva partida');
        
        validateTeams(); // muestra validación de equipos si corresponde

        // Limpiar almacenamiento de puntajes y cualquier snapshot previo
        try{ localStorage.removeItem('game-team-scores'); }catch(e){}

        // Notificar al tablero un reset total y estado consistente (sin turno y multiplicador x1)
        sendMessage({type:'reset_all', payload:{}});
        sendMessage({type:'active_team', payload:{team:null}});
        sendMessage({type:'multiplier', payload:{multiplier: 1}});

        stateEl.textContent = 'Listo';
    });
}

// Función para manejar mensajes entrantes (se define una sola vez)
function handleIncoming(msg){
        if (!msg || !msg.type) return;
            // handle test response from board
            if (msg.type === 'test_response'){
                console.log('[controller] ✅ Respuesta de test recibida del board:', msg);
                setSyncStatus('ok', '🟢 Conexión confirmada!');
                showTerminalMessage('ping board --result=success ✓');
                setTimeout(()=>{ setSyncStatus('idle','Sin actividad'); }, 3000);
                return;
            }
            // handle ACKs from board
            if (msg.type === 'ack'){
                if(msg.id && msg.id === lastAckId){
                    if(ackTimer) clearTimeout(ackTimer);
                    setSyncStatus('idle','');
                }
                return;
            }
            if (msg.type === 'request_init') {
                const payload = {answers, state:stateEl.textContent, question: questionEl.value};
                console.debug('[controller] responding init', payload);
                sendMessage({type:'init', payload});
                return;
            }
            // receive init from controller or other controller instances
            if (msg.type === 'init'){
                // update local answers copy but keep controller authoritative
                // we'll use the init to know total answers count
                // do not auto-assign anything here
                // just update local answers variable so reveal tracking is accurate
                // note: controller is authoritative, but init may come from another window
                // merge minimal fields
                const payload = msg.payload || {};
                if(Array.isArray(payload.answers)){
                    answers = payload.answers.map(a=>({...a}));
                    render();
                }
                return;
            }
        // board indicates all revealed and round ready to assign
        // controller should detect when all answers revealed by listening to reveal events
        if (msg.type === 'round_points'){
            currentRound.points = Number((msg.payload && msg.payload.points) || 0);
            currentRound.teams = (msg.payload && msg.payload.teams) || [];
            // init team scores entries
            (currentRound.teams || []).forEach(t=>{ if(!(t in teamScores)) teamScores[t]=0; });
            persistTeamScores();
            renderTeamScores();
            // store a copy of answers to know how many exist
            // if we have a recent init, answers variable already set; otherwise request init
            if(!answers || answers.length===0){ sendMessage({type:'request_init'}); }
            roundAssignEl.style.display = 'none';
            return;
        }

        if(msg.type === 'reveal'){
            // update revealed state locally (controller sent or other tab)
            const idx = Number(msg.payload && msg.payload.index);
            if(Number.isFinite(idx) && answers[idx]) answers[idx].revealed = true;
            render();
            // check if all real answers are revealed -> then show assign UI
            const realAnswers = answers.filter(a=>a && a.text);
            const allRevealed = realAnswers.length>0 && realAnswers.every(a=>a.revealed);
            if(allRevealed && currentRound){
                const finalPoints = Number(currentRound.accumulatedPoints || 0);
                if(finalPoints > 0){
                    if(activeTeam){
                        sendMessage({type:'assign_points', payload:{team:activeTeam, points: finalPoints, roundNumber: roundNumber}});
                        if(!(activeTeam in teamScores)) teamScores[activeTeam]=0;
                        teamScores[activeTeam] = Number(teamScores[activeTeam]||0) + finalPoints;
                        persistTeamScores();
                        renderTeamScores();
                        roundAssignEl.style.display = 'none';
                        currentRound = {points:0, teams:[], accumulatedPoints:0};
                    } else {
                        // Fallback manual assignment
                        roundTeamButtons.innerHTML = '';
                        (currentRound.teams || []).forEach(t=>{
                            const b = document.createElement('button'); b.textContent = t; b.addEventListener('click', ()=>{
                                sendMessage({type:'assign_points', payload:{team:t, points: finalPoints, roundNumber: roundNumber}});
                                if(!(t in teamScores)) teamScores[t]=0;
                                teamScores[t] = Number(teamScores[t]||0) + finalPoints;
                                persistTeamScores();
                                renderTeamScores();
                                roundAssignEl.style.display = 'none';
                                currentRound = {points:0, teams:[], accumulatedPoints:0};
                            });
                            roundTeamButtons.appendChild(b);
                        });
                        roundAssignEl.style.display = 'block';
                    }
                }
            }
            return;
        }
        // reveal_all removed: controller manages reveals individually; board only visual
        if(msg.type === 'assign_points'){
            const t = (msg.payload && msg.payload.team) || '';
            const pts = Number((msg.payload && msg.payload.points) || 0);
            if(t){ if(!(t in teamScores)) teamScores[t] = 0; teamScores[t] = Number(teamScores[t]||0) + pts; persistTeamScores(); renderTeamScores(); }
        }
        
        // Handle Fast Money unlock notification from board
        if(msg.type === 'unlock_fast_money'){
            console.log('💰 Board notifica: Dinero Rápido desbloqueado');
            unlockFastMoney();
        }
}

// ===== FAST MONEY CONTROLLER FUNCTIONS =====

let currentFastMoneyData = null;
let currentQuestionIndex = 0;
let fastMoneyScore = 0;
let currentParticipant = 1; // 1 o 2
let blockedAnswers = {}; // {questionIndex: [answerIndexes]}
let participantAnswers = {
    participant1: [], // Respuestas del participante 1
    participant2: []  // Respuestas del participante 2
};
let fastMoneySession = {
    questions: [], // {question: string, selectedAnswer: string, points: number}
    totalScore: 0
};

const fastMoneyQuestions = [
    { 
        question: "Nombra un lenguaje de programación popular", 
        answers: ["JavaScript", "Python", "Java", "C++", "C#"], 
        points: [38, 25, 18, 12, 7] 
    },
    { 
        question: "Nombra un navegador web", 
        answers: ["Chrome", "Firefox", "Safari", "Edge", "Opera"], 
        points: [45, 22, 15, 12, 6] 
    },
    { 
        question: "Nombra una red social", 
        answers: ["Facebook", "Instagram", "Twitter", "TikTok", "LinkedIn"], 
        points: [35, 28, 20, 10, 7] 
    },
    { 
        question: "Nombra un sistema operativo", 
        answers: ["Windows", "macOS", "Linux", "Android", "iOS"], 
        points: [40, 25, 15, 12, 8] 
    },
    { 
        question: "Nombra una empresa de tecnología", 
        answers: ["Google", "Apple", "Microsoft", "Amazon", "Meta"], 
        points: [32, 28, 22, 10, 8] 
    }
];

function showFastMoneyController() {
    console.log('🎮 Iniciando controlador de dinero rápido');
    console.log('📊 Preguntas disponibles:', fastMoneyQuestions?.length || 0);
    console.log('📋 Preguntas:', fastMoneyQuestions);
    
    const controller = document.getElementById('fastMoneyController');
    if(!controller) return;
    
    // 🎭 Agregar efecto de telón al botón
    const fastMoneyBtn = document.getElementById('fastMoneyBtn');
    if(fastMoneyBtn) {
        fastMoneyBtn.classList.add('curtain-effect');
        fastMoneyBtn.textContent = '🎭 Preparando Dinero Rápido...';
    }
    
    // 🎭 Enviar señal al board para mostrar telón de transición
    sendMessage({type:'fast_money_curtain', payload:{action: 'start'}});
    
    // Esperar 2 segundos para la animación del telón
    setTimeout(() => {
        controller.style.display = 'flex';
        
        // 🎭 Enviar animación de telón SIEMPRE
        sendMessage({type: 'fast_money_curtain', payload: {}});
        
        // Enviar mensaje al tablero para cambiar a modo Dinero Rápido
        sendMessage({type: 'switch_fast_money', payload: {mode: 'start'}});
        
        // Initialize controller
        initializeFastMoneyController();
        
        // Restaurar botón
        if(fastMoneyBtn) {
            fastMoneyBtn.classList.remove('curtain-effect');
            fastMoneyBtn.innerHTML = '💰 DINERO RÁPIDO <span style="font-size:10px;opacity:0.8">¡ACTIVO!</span>';
        }
        
        showTerminalMessage('fast-money --controller=active --status=ready 💰');
    }, 2000);
    
    // 🔧 Configurar event listeners inmediatamente
    setupFastMoneyEventListeners();
}

function hideFastMoneyController() {
    const controller = document.getElementById('fastMoneyController');
    if(controller) {
        controller.style.display = 'none';
    }
    
    // Send exit message to board
    sendMessage({type: 'switch_fast_money', payload: {mode: 'exit'}});
    
    showTerminalMessage('fast-money --controller=closed --status=inactive 🚪');
}

// 🔧 Configurar event listeners del dinero rápido
function setupFastMoneyEventListeners() {
    console.log('🔧 Configurando event listeners del dinero rápido...');
    
    // Cargar pregunta
    const loadBtn = document.getElementById('loadQuestion');
    console.log('📤 Botón cargar pregunta encontrado:', loadBtn);
    if(loadBtn) {
        loadBtn.removeEventListener('click', loadFastMoneyQuestion); // Evitar duplicados
        loadBtn.addEventListener('click', loadFastMoneyQuestion);
        console.log('✅ Event listener de cargar pregunta configurado');
    } else {
        console.error('❌ Botón loadQuestion no encontrado');
    }
    
    // Botones de revelar (1-5)
    for(let i = 1; i <= 5; i++) {
        const revealBtn = document.getElementById(`reveal${i}`);
        console.log(`🎯 Botón reveal${i} encontrado:`, revealBtn);
        if(revealBtn) {
            // Crear función específica para este botón para evitar problemas de closure
            const revealFunction = function() {
                revealFastMoneyAnswer(i - 1);
            };
            
            // Remover cualquier listener previo usando la referencia almacenada
            if(revealBtn.fastMoneyListener) {
                revealBtn.removeEventListener('click', revealBtn.fastMoneyListener);
            }
            
            // Agregar nuevo listener y guardar referencia
            revealBtn.fastMoneyListener = revealFunction;
            revealBtn.addEventListener('click', revealFunction);
            console.log(`✅ Event listener reveal${i} configurado`);
        } else {
            console.error(`❌ Botón reveal${i} no encontrado`);
        }
    }
    
    // Botones de acción
    const nextBtn = document.getElementById('nextQuestionBtn');
    const duplicateBtn = document.getElementById('duplicateAnswerBtn');
    const revealPointsBtn = document.getElementById('revealPointsBtn');
    const resetBtn = document.getElementById('resetFastMoney');
    const finishBtn = document.getElementById('finishFastMoney');
    const exitBtn = document.getElementById('fastControlExit');
    
    if(nextBtn) {
        nextBtn.removeEventListener('click', nextFastMoneyQuestion);
        nextBtn.addEventListener('click', nextFastMoneyQuestion);
    }
    if(duplicateBtn) {
        duplicateBtn.removeEventListener('click', playDuplicateAnswerSound);
        duplicateBtn.addEventListener('click', playDuplicateAnswerSound);
    }
    if(revealPointsBtn) {
        revealPointsBtn.removeEventListener('click', revealFastMoneyPoints);
        revealPointsBtn.addEventListener('click', revealFastMoneyPoints);
    }
    if(resetBtn) {
        resetBtn.removeEventListener('click', resetFastMoney);
        resetBtn.addEventListener('click', resetFastMoney);
    }
    if(finishBtn) {
        finishBtn.removeEventListener('click', finishFastMoney);
        finishBtn.addEventListener('click', finishFastMoney);
    }
    if(exitBtn) {
        exitBtn.removeEventListener('click', hideFastMoneyController);
        exitBtn.addEventListener('click', hideFastMoneyController);
    }
    
    // Event listener para el target dinámico
    const targetInput = document.getElementById('fastMoneyTarget');
    if(targetInput) {
        targetInput.addEventListener('input', function() {
            const newTarget = this.value;
            document.getElementById('fastControlTarget').textContent = newTarget;
            // Enviar al tablero también
            sendMessage({
                type: 'fast_money_target_update',
                payload: { target: newTarget }
            });
        });
    }
    
    console.log('🔧 Event listeners del dinero rápido configurados');
}

function initializeFastMoneyController() {
    currentQuestionIndex = 0;
    fastMoneyScore = 0;
    currentFastMoneyData = null;
    currentParticipant = 1;
    blockedAnswers = {};
    participantAnswers = {
        participant1: [],
        participant2: []
    };
    
    // 🔄 Limpiar sesión de dinero rápido
    fastMoneySession = {
        questions: [],
        totalScore: 0
    };
    
    // Reset UI
    document.getElementById('fastControlScore').textContent = '0';
    // Actualizar target dinámico
    const targetValue = document.getElementById('fastMoneyTarget').value;
    document.getElementById('fastControlTarget').textContent = targetValue;
    
    // Asegurar que el botón "Siguiente" esté visible al iniciar
    const nextBtn = document.getElementById('nextQuestionBtn');
    if(nextBtn) {
        nextBtn.style.display = 'inline-block';
        nextBtn.disabled = true;
        nextBtn.textContent = '▶️ Siguiente';
    }
    document.getElementById('fastControlStatus').textContent = '🎮 Participante 1 - ¡Listo para comenzar!';
    
    // Reset answers
    for(let i = 1; i <= 5; i++) {
        document.getElementById(`answer${i}`).value = '';
        document.getElementById(`points${i}`).textContent = '--';
        document.getElementById(`reveal${i}`).disabled = true;
        document.querySelector(`[data-answer="${i-1}"]`).classList.remove('revealed');
    }
    
    document.getElementById('nextQuestionBtn').disabled = true;
}

function loadFastMoneyQuestion() {
    console.log('🎯 loadFastMoneyQuestion ejecutado');
    const selectedIndex = document.getElementById('questionSelect').value;
    const question = fastMoneyQuestions[selectedIndex];
    
    console.log('📝 Pregunta seleccionada:', question);
    if(!question) {
        console.error('❌ No se encontró la pregunta');
        return;
    }
    
    currentFastMoneyData = question;
    currentQuestionIndex = parseInt(selectedIndex);
    
    // No actualizamos currentQuestionText porque se removió del HTML
    
    // Load answers y manejar respuestas bloqueadas
    const questionBlocked = blockedAnswers[currentQuestionIndex] || [];
    
    for(let i = 0; i < 5; i++) {
        const answerInput = document.getElementById(`answer${i + 1}`);
        const pointsSpan = document.getElementById(`points${i + 1}`);
        const revealBtn = document.getElementById(`reveal${i + 1}`);
        const container = document.querySelector(`[data-answer="${i}"]`);
        
        answerInput.value = question.answers[i] || '';
        pointsSpan.textContent = question.points[i] || 0;
        
        // Verificar si esta respuesta está bloqueada
        const isBlocked = questionBlocked.includes(i);
        
        if(isBlocked) {
            // Respuesta bloqueada del participante anterior
            revealBtn.disabled = true;
            revealBtn.textContent = 'Bloqueada';
            revealBtn.style.background = '#ef4444';
            container.style.opacity = '0.6';
            container.classList.add('blocked-answer');
        } else {
            // Respuesta disponible - restaurar completamente
            revealBtn.disabled = false;
            revealBtn.textContent = 'Revelar';
            revealBtn.style.background = 'var(--accent2)';
            revealBtn.style.cursor = 'pointer';
            revealBtn.style.opacity = '1';
            container.style.opacity = '1';
            container.classList.remove('blocked-answer', 'revealed');
        }
    }
    
    // Send question to board
    const messageData = {
        type: 'fast_money_question',
        payload: {
            question: question.question,
            index: currentQuestionIndex
        }
    };
    console.log('📤 Enviando mensaje al tablero:', messageData);
    sendMessage(messageData);
    console.log('✅ Mensaje enviado correctamente');
    
    document.getElementById('fastControlStatus').textContent = `Pregunta ${currentQuestionIndex + 1} cargada - ¡Lista para revelar respuestas!`;
    document.getElementById('nextQuestionBtn').disabled = false;
}

function revealFastMoneyAnswer(answerIndex) {
    console.log('🎯 revealFastMoneyAnswer ejecutado, índice:', answerIndex);
    console.log('📊 currentFastMoneyData:', currentFastMoneyData);
    
    // 🔒 Prevenir cambios durante revelado automático
    if(autoRevealInterval !== null) {
        console.log('⚠️ Revelado automático en progreso, ignorando acción manual');
        document.getElementById('fastControlStatus').textContent = '⚠️ Espera a que termine el revelado automático';
        return;
    }
    
    if(!currentFastMoneyData) {
        console.error('❌ No hay datos de Fast Money cargados');
        return;
    }
    
    // 🔒 Verificar si ya fue revelada para evitar doble puntuación
    const answerContainer = document.querySelector(`[data-answer="${answerIndex}"]`);
    if(answerContainer && answerContainer.classList.contains('revealed')) {
        console.log('⚠️ Respuesta ya revelada, ignorando');
        // Mostrar alerta visual
        showTerminalMessage(`⚠️ fast-money --error="Respuesta ya revelada" --answer=${answerIndex + 1} 🚫`);
        document.getElementById('fastControlStatus').textContent = `⚠️ La respuesta ${answerIndex + 1} ya fue revelada`;
        return;
    }
    
    // 🔒 Verificar si el botón ya está deshabilitado
    const revealButton = document.getElementById(`reveal${answerIndex + 1}`);
    if(revealButton && revealButton.disabled) {
        console.log('⚠️ Botón ya deshabilitado, ignorando');
        return;
    }
    
    const answer = currentFastMoneyData.answers[answerIndex];
    const points = currentFastMoneyData.points[answerIndex];
    
    console.log('📝 Respuesta:', answer, 'Puntos:', points);
    
    if(!answer) {
        console.error('❌ No se encontró respuesta para el índice:', answerIndex);
        return;
    }
    
    // 📝 Registrar respuesta seleccionada y bloquearla para el siguiente participante
    const questionName = currentFastMoneyData.question || `Pregunta ${currentQuestionIndex + 1}`;
    const participantKey = `participant${currentParticipant}`;
    
    // Guardar respuesta del participante actual
    participantAnswers[participantKey].push({
        questionIndex: currentQuestionIndex,
        answerIndex: answerIndex,
        answer: answer,
        points: points
    });
    
    // Bloquear esta respuesta para el siguiente participante
    if(!blockedAnswers[currentQuestionIndex]) {
        blockedAnswers[currentQuestionIndex] = [];
    }
    blockedAnswers[currentQuestionIndex].push(answerIndex);
    
    fastMoneySession.questions.push({
        question: questionName,
        selectedAnswer: answer,
        points: points,
        participant: currentParticipant
    });
    
    // Update local score
    console.log('💰 Puntaje antes:', fastMoneyScore, '+ puntos:', points);
    fastMoneyScore += points;
    console.log('💰 Puntaje después:', fastMoneyScore);
    fastMoneySession.totalScore = fastMoneyScore;
    document.getElementById('fastControlScore').textContent = fastMoneyScore;
    
    // 🔒 Marcar como revelada INMEDIATAMENTE para prevenir doble clic
    document.querySelector(`[data-answer="${answerIndex}"]`).classList.add('revealed');
    
    // Deshabilitar y cambiar estilo del botón revelado
    const revealBtn = document.getElementById(`reveal${answerIndex + 1}`);
    if(revealBtn) {
        revealBtn.disabled = true;
        revealBtn.textContent = '✅ Revelada';
        revealBtn.style.background = '#10b981';
        revealBtn.style.cursor = 'not-allowed';
        revealBtn.style.opacity = '0.8';
    }
    
    // 🚫 DESHABILITAR TODOS LOS DEMÁS BOTONES de esta pregunta
    if(currentFastMoneyData && currentFastMoneyData.answers) {
        for(let i = 0; i < currentFastMoneyData.answers.length; i++) {
            if(i !== answerIndex) { // No tocar el botón que acabamos de revelar
                const otherBtn = document.getElementById(`reveal${i + 1}`);
                if(otherBtn && !otherBtn.disabled) {
                    otherBtn.disabled = true;
                    otherBtn.textContent = '🚫 Bloqueada';
                    otherBtn.style.background = '#ef4444';
                    otherBtn.style.cursor = 'not-allowed';
                    otherBtn.style.opacity = '0.5';
                    console.log(`🚫 Botón ${i + 1} deshabilitado (respuesta no seleccionada)`);
                }
            }
        }
    }
    
    console.log(`🔒 Respuesta ${answerIndex + 1} revelada y TODOS los demás botones deshabilitados`);
    
    // Send to board SIN PUNTOS - solo mostrar respuesta
    sendMessage({
        type: 'fast_money_reveal',
        payload: {
            answerIndex: answerIndex,
            answer: answer,
            points: 0, // No mostrar puntos reales en el tablero
            totalScore: 0, // No mostrar total en el tablero
            hidePoints: true, // Bandera para indicar que se oculten los puntos
            participant: currentParticipant, // Indicar qué participante está jugando
            questionIndex: currentQuestionIndex // Indicar qué pregunta
        }
    });
    
    // Update status
    document.getElementById('fastControlStatus').textContent = `¡+${points} puntos! Total: ${fastMoneyScore}/200`;
    
    showTerminalMessage(`fast-money --reveal="${answer}" --points=${points} --total=${fastMoneyScore} ✅`);
}

function nextFastMoneyQuestion() {
    // 🔒 Prevenir cambios durante revelado automático
    if(autoRevealInterval !== null) {
        console.log('⚠️ No se puede cambiar pregunta durante revelado automático');
        document.getElementById('fastControlStatus').textContent = '⚠️ Espera a que termine el revelado automático';
        return;
    }
    
    if(currentQuestionIndex < fastMoneyQuestions.length - 1) {
        const nextIndex = currentQuestionIndex + 1;
        document.getElementById('questionSelect').value = nextIndex;
        loadFastMoneyQuestion();
        
        // Actualizar texto del botón si es la última pregunta
        if(nextIndex === fastMoneyQuestions.length - 1) {
            document.getElementById('nextQuestionBtn').textContent = '👥 Siguiente Participante';
        }
    } else {
        // Cambiar de participante
        switchToNextParticipant();
    }
}

function switchToNextParticipant() {
    if(currentParticipant === 1) {
        // 🔒 Asegurar que las respuestas del Participante 1 mantengan puntos ocultos
        sendMessage({
            type: 'hide_participant_points', 
            payload: {
                participant: 1
            }
        });
        
        // Cambiar al participante 2
        currentParticipant = 2;
        
        // Resetear a la primera pregunta
        currentQuestionIndex = 0;
        document.getElementById('questionSelect').value = 0;
        
        // 🎭 Enviar animación de telón para el participante 2
        sendMessage({type: 'fast_money_curtain', payload: {}});
        
        // Cambiar UI para participante 2
        document.getElementById('fastControlStatus').textContent = '🎮 ¡Turno del Participante 2! Comenzando desde la pregunta 1...';
        
        // Cambiar botón
        const nextBtn = document.getElementById('nextQuestionBtn');
        nextBtn.textContent = '▶️ Siguiente';
        nextBtn.disabled = true;
        
        // Mostrar mensaje
        showTerminalMessage('fast-money --participant=2 --reset-questions --hide-p1-answers --status=ready 👥');
        
        // Cargar primera pregunta automáticamente
        setTimeout(() => {
            loadFastMoneyQuestion();
        }, 1000);
    } else {
        // 🔒 Ocultar puntos del Participante 2 también al terminar
        sendMessage({
            type: 'hide_participant_points', 
            payload: {
                participant: 2
            }
        });
        
        // Ya terminaron ambos participantes
        document.getElementById('fastControlStatus').textContent = '¡Ambos participantes completaron el Dinero Rápido! Presiona "Revelar Puntos" para mostrar resultados.';
        
        // Ocultar completamente el botón "Siguiente"
        const nextBtn = document.getElementById('nextQuestionBtn');
        if(nextBtn) {
            nextBtn.style.display = 'none';
        }
    }
}

function playDuplicateAnswerSound() {
    // Crear efecto visual y sonoro para respuesta duplicada
    document.getElementById('fastControlStatus').textContent = '🚫 ¡Respuesta ya mencionada por el participante anterior!';
    
    // Efecto visual de error
    const controller = document.getElementById('fastMoneyController');
    if(controller) {
        controller.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            controller.style.animation = '';
        }, 500);
    }
    
    // Sonido de error (si existe la función)
    if(typeof playErrorSound === 'function') {
        playErrorSound();
    }
    
    // Mensaje en terminal
    showTerminalMessage('fast-money --duplicate-answer --sound=error --status=blocked 🚫');
    
    // Reset status después de 3 segundos
    setTimeout(() => {
        document.getElementById('fastControlStatus').textContent = `Participante ${currentParticipant} - Pregunta ${currentQuestionIndex + 1}`;
    }, 3000);
}

function revealFastMoneyPoints() {
    const targetInput = document.getElementById('fastMoneyTarget');
    const currentTarget = targetInput ? parseInt(targetInput.value) || 200 : 200;
    
    // Iniciar revelado automático de todas las respuestas
    startAutomaticReveal(currentTarget);
    
    showTerminalMessage(`fast-money --reveal-mode=automatic --starting 🎬`);
}

function resetFastMoney() {
    if(confirm('¿Estás seguro de reiniciar el Dinero Rápido?')) {
        initializeFastMoneyController();
        sendMessage({type: 'fast_money_reset', payload: {}});
        showTerminalMessage('fast-money --action=reset --status=initialized 🔄');
    }
}

function finishFastMoney() {
    const targetInput = document.getElementById('fastMoneyTarget');
    const currentTarget = targetInput ? parseInt(targetInput.value) || 200 : 200;
    
    // Calcular total real
    let totalScore = 0;
    for(let participant = 1; participant <= 2; participant++) {
        const participantKey = `participant${participant}`;
        if(participantAnswers[participantKey]) {
            for(let answer of participantAnswers[participantKey]) {
                totalScore += answer.points;
            }
        }
    }
    
    const success = totalScore >= currentTarget;
    
    // Mostrar total final directamente
    sendMessage({
        type: 'fast_money_reveal_total',
        payload: {
            totalScore: totalScore,
            target: currentTarget,
            success: success
        }
    });
    
    // 🎬 Enviar animación específica según resultado
    setTimeout(() => {
        sendMessage({
            type: success ? 'fast_money_victory_animation' : 'fast_money_defeat_animation',
            payload: {
                totalScore: totalScore,
                target: currentTarget,
                difference: success ? totalScore - currentTarget : currentTarget - totalScore
            }
        });
    }, 1000); // Delay para que se vea primero el total
    
    // Actualizar UI final
    const message = success ? 
        `🎉 ¡¡¡FELICIDADES!!! ¡${totalScore}/${currentTarget} - DINERO RÁPIDO COMPLETADO!` : 
        `😔 Total: ${totalScore}/${currentTarget} - ¡Faltaron ${currentTarget - totalScore} puntos!`;
    
    document.getElementById('fastControlStatus').textContent = message;
    
    // Deshabilitar botón finalizar
    const finishBtn = document.getElementById('finishFastMoney');
    if(finishBtn) {
        finishBtn.textContent = '✅ Finalizado';
        finishBtn.disabled = true;
    }
    
    showTerminalMessage(`fast-money --final-total=${totalScore}/${currentTarget} --success=${success} 🏆`);
}
// 🎯 Variables para el revelado
let currentRevealStep = 0;
let totalRevealQuestions = 0;
let revealTarget = 200;
let revealSuccess = false;
let autoRevealInterval = null;

// 🎬 Función para iniciar revelado automático
function startAutomaticReveal(target) {
    // Reiniciar variables
    currentRevealStep = 0;
    revealTarget = target;
    
    // Contar cuántas respuestas hay para revelar (ambos participantes)
    totalRevealQuestions = 0;
    for(let participant = 1; participant <= 2; participant++) {
        const participantKey = `participant${participant}`;
        if(participantAnswers[participantKey]) {
            totalRevealQuestions += participantAnswers[participantKey].length;
        }
    }
    
    if(totalRevealQuestions === 0) {
        alert('No hay respuestas para revelar');
        return;
    }
    
    // Actualizar UI
    document.getElementById('fastControlStatus').textContent = `🎬 Revelando automáticamente... 0/${totalRevealQuestions}`;
    
    // Deshabilitar botón revelar durante la secuencia
    const revealBtn = document.getElementById('revealPointsBtn');
    if(revealBtn) {
        revealBtn.textContent = '🎬 Revelando...';
        revealBtn.disabled = true;
    }
    
    // Iniciar secuencia automática (cada 2 segundos)
    autoRevealInterval = setInterval(() => {
        revealNextAnswerAutomatic();
    }, 2000);
    
    showTerminalMessage(`fast-money --auto-reveal=starting --total=${totalRevealQuestions} 🎬`);
}

// 🎯 Función para revelar automáticamente la siguiente respuesta
function revealNextAnswerAutomatic() {
    if(currentRevealStep >= totalRevealQuestions) {
        // Terminar secuencia automática
        clearInterval(autoRevealInterval);
        autoRevealInterval = null;
        
        // Calcular scores individuales de cada participante
        let participant1Score = 0;
        let participant2Score = 0;
        
        if(participantAnswers.participant1) {
            for(let answer of participantAnswers.participant1) {
                participant1Score += answer.points;
            }
        }
        
        if(participantAnswers.participant2) {
            for(let answer of participantAnswers.participant2) {
                participant2Score += answer.points;
            }
        }
        
        // Enviar scores individuales al tablero
        sendMessage({
            type: 'update_participant_scores',
            payload: {
                participant1Score: participant1Score,
                participant2Score: participant2Score
            }
        });
        
        // Actualizar UI final
        document.getElementById('fastControlStatus').textContent = `🎊 ¡Revelado completo! P1: ${participant1Score} pts | P2: ${participant2Score} pts | Total: ${participant1Score + participant2Score}`;
        
        const revealBtn = document.getElementById('revealPointsBtn');
        if(revealBtn) {
            revealBtn.textContent = '✅ Auto-Revelado Completo';
            revealBtn.disabled = true;
        }
        
        // Hacer prominente el botón finalizar
        const finishBtn = document.getElementById('finishFastMoney');
        if(finishBtn) {
            finishBtn.style.background = '#ef4444';
            finishBtn.style.animation = 'pulse 1s ease-in-out infinite';
            finishBtn.textContent = '🏆 Ver Total Final';
        }
        
        showTerminalMessage(`fast-money --auto-reveal=completed --p1=${participant1Score} --p2=${participant2Score} --total=${participant1Score + participant2Score} 🎊`);
        return;
    }
    
    // Encontrar la siguiente respuesta a revelar
    let answerFound = false;
    let currentCount = 0;
    
    for(let participant = 1; participant <= 2 && !answerFound; participant++) {
        const participantKey = `participant${participant}`;
        if(participantAnswers[participantKey]) {
            for(let i = 0; i < participantAnswers[participantKey].length; i++) {
                if(currentCount === currentRevealStep) {
                    const answerData = participantAnswers[participantKey][i];
                    
                    // Revelar esta respuesta específica en el tablero
                    sendMessage({
                        type: 'fast_money_reveal_step',
                        payload: {
                            participant: participant,
                            questionIndex: answerData.questionIndex,
                            answer: answerData.answer,
                            points: answerData.points,
                            stepNumber: currentRevealStep + 1,
                            totalSteps: totalRevealQuestions
                        }
                    });
                    
                    // Actualizar UI
                    document.getElementById('fastControlStatus').textContent = 
                        `🎬 Auto-revelado: P${participant} - "${answerData.answer}" = ${answerData.points} pts (${currentRevealStep + 1}/${totalRevealQuestions})`;
                    
                    currentRevealStep++;
                    answerFound = true;
                    break;
                }
                currentCount++;
            }
        }
    }
}

// 🎬 Función para iniciar revelado paso a paso
function startStepByStepReveal(target, success) {
    // Reiniciar variables
    currentRevealStep = 0;
    revealTarget = target;
    revealSuccess = success;
    
    // Contar cuántas respuestas hay para revelar (ambos participantes)
    totalRevealQuestions = 0;
    for(let participant = 1; participant <= 2; participant++) {
        const participantKey = `participant${participant}`;
        if(participantAnswers[participantKey]) {
            totalRevealQuestions += participantAnswers[participantKey].length;
        }
    }
    
    if(totalRevealQuestions === 0) {
        alert('No hay respuestas para revelar');
        return;
    }
    
    // Cambiar UI para revelado paso a paso
    document.getElementById('fastControlStatus').textContent = `🎬 ¡Listo para revelar! ${totalRevealQuestions} respuestas por mostrar`;
    
    // Cambiar botón "Revelar Puntos" a "Siguiente Respuesta"
    const revealBtn = document.getElementById('revealPointsBtn');
    if(revealBtn) {
        revealBtn.textContent = '🎯 Revelar Siguiente';
        revealBtn.onclick = revealNextAnswer;
        revealBtn.disabled = false;
    }
    
    // Preparar botón "Finalizar" para el total final
    const finishBtn = document.getElementById('finishFastMoney');
    if(finishBtn) {
        finishBtn.textContent = '🏆 Finalizar';
        finishBtn.disabled = false;
        finishBtn.style.background = '#10b981';
        finishBtn.style.animation = '';
    }
    
    showTerminalMessage(`fast-money --reveal-mode=step-by-step --total=${totalRevealQuestions} --ready 🎬`);
}

// 🎯 Función para revelar la siguiente respuesta
function revealNextAnswer() {
    if(currentRevealStep >= totalRevealQuestions) {
        // Ya se revelaron todas las respuestas, preparar para finalizar
        document.getElementById('fastControlStatus').textContent = '🎊 ¡Todas las respuestas reveladas! Presiona "Finalizar" para ver el total.';
        
        // Cambiar botón "Revelar Siguiente" a completado
        const revealBtn = document.getElementById('revealPointsBtn');
        if(revealBtn) {
            revealBtn.textContent = '✅ Respuestas Reveladas';
            revealBtn.disabled = true;
        }
        
        // Hacer que el botón "Finalizar" sea más prominente
        const finishBtn = document.getElementById('finishFastMoney');
        if(finishBtn) {
            finishBtn.style.background = '#ef4444';
            finishBtn.style.animation = 'pulse 1s ease-in-out infinite';
            finishBtn.textContent = '🏆 Ver Total Final';
        }
        
        showTerminalMessage(`fast-money --all-revealed --ready-for-total 🎊`);
        return;
    }
    
    // Encontrar la siguiente respuesta a revelar
    let answerFound = false;
    let currentCount = 0;
    
    for(let participant = 1; participant <= 2 && !answerFound; participant++) {
        const participantKey = `participant${participant}`;
        if(participantAnswers[participantKey]) {
            for(let i = 0; i < participantAnswers[participantKey].length; i++) {
                if(currentCount === currentRevealStep) {
                    const answerData = participantAnswers[participantKey][i];
                    
                    // Revelar esta respuesta específica en el tablero
                    sendMessage({
                        type: 'fast_money_reveal_step',
                        payload: {
                            participant: participant,
                            questionIndex: answerData.questionIndex,
                            answer: answerData.answer,
                            points: answerData.points,
                            stepNumber: currentRevealStep + 1,
                            totalSteps: totalRevealQuestions
                        }
                    });
                    
                    // Actualizar UI
                    document.getElementById('fastControlStatus').textContent = 
                        `🎯 Revelado: P${participant} - "${answerData.answer}" = ${answerData.points} pts (${currentRevealStep + 1}/${totalRevealQuestions})`;
                    
                    // Actualizar botón
                    const revealBtn = document.getElementById('revealPointsBtn');
                    if(currentRevealStep + 1 >= totalRevealQuestions) {
                        revealBtn.textContent = '🏆 Mostrar Total Final';
                    } else {
                        revealBtn.textContent = `🎯 Revelar Siguiente (${currentRevealStep + 2}/${totalRevealQuestions})`;
                    }
                    
                    currentRevealStep++;
                    answerFound = true;
                    break;
                }
                currentCount++;
            }
        }
    }
}

// 🏆 Función para revelar el total final
function revealFinalTotal() {
    // Calcular total real
    let totalScore = 0;
    for(let participant = 1; participant <= 2; participant++) {
        const participantKey = `participant${participant}`;
        if(participantAnswers[participantKey]) {
            for(let answer of participantAnswers[participantKey]) {
                totalScore += answer.points;
            }
        }
    }
    
    // Enviar revelación final
    sendMessage({
        type: 'fast_money_reveal_total',
        payload: {
            totalScore: totalScore,
            target: revealTarget,
            success: totalScore >= revealTarget
        }
    });
    
    // Actualizar UI final
    const message = totalScore >= revealTarget ? 
        `🎉 ¡¡¡FELICIDADES!!! ¡${totalScore}/${revealTarget} - DINERO RÁPIDO COMPLETADO!` : 
        `😔 Total: ${totalScore}/${revealTarget} - ¡Mejor suerte la próxima vez!`;
    
    document.getElementById('fastControlStatus').textContent = message;
    
    // Restaurar botón
    const revealBtn = document.getElementById('revealPointsBtn');
    if(revealBtn) {
        revealBtn.textContent = '✅ Revelación Completa';
        revealBtn.disabled = true;
    }
    
    showTerminalMessage(`fast-money --final-total=${totalScore}/${revealTarget} --success=${totalScore >= revealTarget} 🏆`);
}

// 📊 Mostrar resumen de respuestas del dinero rápido en el controller
function showFastMoneyResults() {
    const resultsHtml = `
        <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                    background: var(--bg); border: 2px solid var(--accent); border-radius: 12px; 
                    padding: 20px; max-width: 500px; z-index: 10000; box-shadow: 0 8px 32px rgba(0,0,0,0.8);">
            <h3 style="color: var(--accent); margin: 0 0 15px 0; text-align: center;">
                🏆 Resumen Dinero Rápido
            </h3>
            <div style="max-height: 300px; overflow-y: auto;">
                ${fastMoneySession.questions.map((item, index) => `
                    <div style="background: rgba(102,252,241,0.1); padding: 10px; margin: 8px 0; border-radius: 6px;">
                        <div style="font-weight: bold; color: var(--accent2); margin-bottom: 4px;">
                            ${item.question}
                        </div>
                        <div style="color: var(--text); display: flex; justify-content: space-between;">
                            <span>→ ${item.selectedAnswer}</span>
                            <span style="color: #ffd700; font-weight: bold;">${item.points} pts</span>
                        </div>
                    </div>
                `).join('')}
            </div>
            <div style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(102,252,241,0.3);">
                <div style="font-size: 18px; font-weight: bold; color: #ffd700; margin-bottom: 10px;">
                    Total: ${fastMoneySession.totalScore} puntos
                </div>
                <button onclick="closeFastMoneyResults()" 
                        style="background: var(--accent); color: var(--bg); border: none; padding: 8px 16px; 
                               border-radius: 6px; cursor: pointer; font-weight: bold;">
                    Cerrar
                </button>
            </div>
        </div>
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(0,0,0,0.7); z-index: 9999;" onclick="closeFastMoneyResults()"></div>
    `;
    
    const resultsContainer = document.createElement('div');
    resultsContainer.id = 'fastMoneyResultsModal';
    resultsContainer.innerHTML = resultsHtml;
    document.body.appendChild(resultsContainer);
}

function closeFastMoneyResults() {
    const modal = document.getElementById('fastMoneyResultsModal');
    if(modal) modal.remove();
}

// 🎯 Actualizar meta del dinero rápido
function updateFastMoneyTarget() {
    const targetInput = document.getElementById('fastMoneyTarget');
    const targetDisplay = document.getElementById('fastControlTarget');
    
    if(!targetInput || !targetDisplay) return;
    
    const newTarget = parseInt(targetInput.value) || 200;
    targetDisplay.textContent = newTarget;
    
    // Enviar al tablero
    sendMessage({
        type: 'fast_money_target_update',
        payload: {
            target: newTarget
        }
    });
    
    showTerminalMessage(`fast-money --target=${newTarget} --updated=true 🎯`);
}

// Event listeners for Fast Money Controller
document.addEventListener('DOMContentLoaded', function() {
    // Load question button
    document.getElementById('loadQuestion')?.addEventListener('click', loadFastMoneyQuestion);
    
    // Reveal buttons
    for(let i = 1; i <= 5; i++) {
        document.getElementById(`reveal${i}`)?.addEventListener('click', () => revealFastMoneyAnswer(i - 1));
    }
    
    // Action buttons
    document.getElementById('nextQuestionBtn')?.addEventListener('click', nextFastMoneyQuestion);
    document.getElementById('resetFastMoney')?.addEventListener('click', resetFastMoney);
    document.getElementById('finishFastMoney')?.addEventListener('click', finishFastMoney);
    document.getElementById('fastControlExit')?.addEventListener('click', hideFastMoneyController);
    
    // Fast Money Target change
    document.getElementById('fastMoneyTarget')?.addEventListener('change', updateFastMoneyTarget);
});

// logging disabled
function logCtrl(text){}

// Strike (X) management
function updateStrikeDisplay(){
    const strikeCountEl = document.getElementById('strikeCount');
    if(strikeCountEl){
        strikeCountEl.textContent = `X: ${strikeCount}/3`;
        strikeCountEl.style.color = strikeCount >= 3 ? '#dc2626' : '#ef4444';
    }
}

// Add strike button listener
const addStrikeBtn = document.getElementById('addStrike');
if(addStrikeBtn){
    addStrikeBtn.addEventListener('click', ()=>{
        // increment strike count for the team currently en turno
        if(strikeCount < 3){
            strikeCount++;
            updateStrikeDisplay();
            sendMessage({type:'update_strikes', payload:{count: strikeCount}});
            
            // Play error sound
            playErrorSound();
            
            // Reset timer when X is added (wrong answer)
            resetTimer();
        }

        // On 3 strikes, perform steal: award round points to the OTHER team
        if(strikeCount >= 3){
            handleThreeStrikesSteal();
        }
    });
}

render();
updateStrikeDisplay();

// persist & render helpers for controller
function persistTeamScores(){ try{ localStorage.setItem('game-team-scores', JSON.stringify(teamScores)); }catch(e){} }
// Function to check for winners after score updates
function checkForWinner() {
    // Fast Money is now available immediately when there are teams with scores
    // The winner will be determined by who has the most points when Fast Money is triggered
    const teamCount = Object.keys(teamScores).length;
    const hasScores = Object.values(teamScores).some(score => score > 0);
    
    if (teamCount >= 2 && hasScores) {
        // Enable Fast Money when there are teams and at least one has points
        const fastMoneyBtn = document.getElementById('fastMoneyBtn');
        if (fastMoneyBtn && fastMoneyBtn.disabled) {
            unlockFastMoney();
            showTerminalMessage(`fast-money --available --teams=${teamCount} 🎯`);
        }
    }
}

function renderTeamScores(){ const el = document.getElementById('teamScoresDisplay'); if(!el) return; el.innerHTML = '';
    Object.keys(teamScores).forEach(name=>{ const d = document.createElement('div'); d.style.padding='8px'; d.style.border='1px solid #e2e8f0'; d.style.borderRadius='6px'; d.style.minWidth='120px'; d.innerHTML = `<div style='font-size:12px;color:#475569'>${escapeHtml(name)}</div><div style='font-weight:800;font-size:18px'>${String(teamScores[name]||0).padStart(3,'0')}</div>`; el.appendChild(d); }); 
    // Check for winner after rendering scores
    checkForWinner();
}

// When a team reaches 3 strikes, show steal attempt UI
function handleThreeStrikesSteal(){
    const teams = currentRound && currentRound.teams ? currentRound.teams.slice() : [];
    if(teams.length < 2){
        // Not enough teams to steal; just reset strikes
        strikeCount = 0; updateStrikeDisplay(); sendMessage({type:'update_strikes', payload:{count: strikeCount}}); return;
    }
    // Determine the other team relative to the active team
    let otherTeam = null;
    if(activeTeam){ otherTeam = teams.find(t=> t !== activeTeam) || null; }
    if(!otherTeam){
        // if no activeTeam selected, choose the first team that isn't empty
        otherTeam = teams[0];
        if(activeTeam === otherTeam && teams[1]) otherTeam = teams[1];
    }

    const finalPoints = Number(currentRound && currentRound.accumulatedPoints ? currentRound.accumulatedPoints : 0);

    // Send a 'steal' event so the board shows the banner
    sendMessage({type:'steal', payload:{toTeam: otherTeam || '', fromTeam: activeTeam || '', points: finalPoints}});

    // Show steal attempt UI instead of auto-assigning
    if(stealAttemptEl && stealTeamNameEl){
        stealTeamNameEl.textContent = otherTeam || '';
        stealAttemptEl.style.display = 'block';
        
        // Store steal context for button handlers
        stealAttemptEl.dataset.stealTeam = otherTeam || '';
        stealAttemptEl.dataset.originalTeam = activeTeam || '';
        stealAttemptEl.dataset.points = String(finalPoints);
    }
}

// Steal attempt button handlers
if(stealSuccessBtn){
    stealSuccessBtn.addEventListener('click', ()=>{
        const stealTeam = stealAttemptEl.dataset.stealTeam;
        
        // Get CURRENT accumulated points (including any answers revealed during steal)
        const basePoints = Number(currentRound && currentRound.accumulatedPoints ? currentRound.accumulatedPoints : 0);
        const points = basePoints * pointMultiplier; // Apply multiplier!
        
        // Check if there are any unrevealed answers
        if(currentRound && Array.isArray(currentRound.answers) && currentRound.answers.length > 0){
            const hasUnrevealedAnswer = currentRound.answers.some(ans => !ans.revealed);
            if (!hasUnrevealedAnswer) {
                alert('⚠️ No hay respuestas sin revelar. El equipo no puede acertar el robo.');
                return;
            }
        }
        
        console.log(`💰 Robo exitoso! ${basePoints} x${pointMultiplier} = ${points} puntos a ${stealTeam}`);
        
        // Award points to the team that stole
        if(stealTeam && points >= 0){
            sendMessage({type:'assign_points', payload:{team: stealTeam, points: points, roundNumber: roundNumber}});
            if(!(stealTeam in teamScores)) teamScores[stealTeam] = 0;
            teamScores[stealTeam] = Number(teamScores[stealTeam]||0) + points;
            persistTeamScores();
            renderTeamScores();
        }
        
        // Reset and hide
        finishStealAttempt();
    });
}

if(stealFailBtn){
    stealFailBtn.addEventListener('click', ()=>{
        const originalTeam = stealAttemptEl.dataset.originalTeam;
        
        console.log('❌ Robo fallido! Revelando todas las respuestas restantes...');
        
        // Reveal all unrevealed answers automatically
        if(currentRound && Array.isArray(currentRound.answers)) {
            const unrevealedAnswers = currentRound.answers.filter(ans => !ans.revealed);
            if(unrevealedAnswers.length > 0) {
                console.log('📋 Revelando', unrevealedAnswers.length, 'respuestas restantes');
                unrevealedAnswers.forEach((ans) => {
                    ans.revealed = true;
                    console.log('  -', ans.text, ':', ans.count, 'puntos');
                });
                
                // Update UI and send to board
                render();
                sendMessage({type:'state', payload:{question, answers: currentRound.answers}});
            }
        }
        
        // Get CURRENT accumulated points (including any answers revealed during steal)
        const basePoints = Number(currentRound && currentRound.accumulatedPoints ? currentRound.accumulatedPoints : 0);
        const points = basePoints * pointMultiplier; // Apply multiplier!
        
        console.log(`💰 Robo fallido! ${basePoints} x${pointMultiplier} = ${points} puntos al equipo original: ${originalTeam}`);
        
        // Award points to the original team (the one that had control)
        if(originalTeam && points >= 0){
            sendMessage({type:'assign_points', payload:{team: originalTeam, points: points, roundNumber: roundNumber}});
            if(!(originalTeam in teamScores)) teamScores[originalTeam] = 0;
            teamScores[originalTeam] = Number(teamScores[originalTeam]||0) + points;
            persistTeamScores();
            renderTeamScores();
        }
        
        // Reset and hide
        finishStealAttempt();
    });
}

function finishStealAttempt(){
    // Hide steal UI
    if(stealAttemptEl) stealAttemptEl.style.display = 'none';
    
    // Reset round & strikes, clear active turn
    const teams = currentRound && currentRound.teams ? currentRound.teams.slice() : [];
    strikeCount = 0; 
    updateStrikeDisplay(); 
    sendMessage({type:'update_strikes', payload:{count: strikeCount}});
    currentRound = {points:0, teams: teams, accumulatedPoints:0, roundNumber: currentRound.roundNumber};
    activeTeam = null; 
    if(turnControls && activeTeamButtons) Array.from(activeTeamButtons.children).forEach(c=> c.classList.remove('selected'));
    sendMessage({type:'active_team', payload:{team:null}});
    if(roundAssignEl) roundAssignEl.style.display = 'none';
}

// load persisted scores
try{ const s = localStorage.getItem('game-team-scores'); if(s) teamScores = JSON.parse(s) || {}; }catch(e){}
renderTeamScores();

// Initialize team input state on page load
validateTeams();

// small helper to avoid XSS in injected text (same as board)
function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

// attempt to pre-load answers from storage if present to have a fast start
try{ const st = localStorage.getItem('game-100mx'); if(st){ const parsed = JSON.parse(st); if(parsed && parsed.msg && parsed.msg.payload && Array.isArray(parsed.msg.payload.answers)){ answers = parsed.msg.payload.answers.map(a=>({...a})); render(); } } }catch(e){}

// Check if there's a question loaded from the question bank
try{
    const loadedQ = localStorage.getItem('game-load-question');
    if(loadedQ){
        const data = JSON.parse(loadedQ);
        questionEl.value = data.question;
        answers = data.answers.map(a => ({...a, revealed: false, correct: false}));
        render();
        // Clear the temp storage
        localStorage.removeItem('game-load-question');
        alert('✅ Pregunta cargada desde el Banco de Preguntas');
        
        // Auto-send to board

        const payload = {answers, state:'Pregunta cargada', question: questionEl.value};
        console.debug('[controller] auto-sending loaded question', payload);
        sendMessage({type:'init', payload});
        stateEl.textContent = 'Pregunta cargada';
    }
}catch(e){
    console.error('Error loading question from bank:', e);
}

// ===== Juego rápido (2 personas por equipo, 5 preguntas cada uno) =====
const quickGameBtn = document.getElementById('quickGameBtn');
let quickGameState = null; // holds current quick game data

function playXSound(){
    // Use the existing error sound as the 'X' audio cue
    playErrorSound();
}

async function startQuickGame(){
    // Open the quick game control page in a new tab (host controls questions and reveals)
    window.open('/quickgame-control', '_blank');
}

function openQuickGameUI(){
    // build a simple modal-like panel
    let panel = document.getElementById('quickGamePanel');
    if(!panel){
        panel = document.createElement('div'); panel.id='quickGamePanel';
        Object.assign(panel.style, {position:'fixed',right:'20px',bottom:'20px',width:'420px',background:'#081018',color:'#cde',padding:'12px',border:'1px solid rgba(102,252,241,0.12)',borderRadius:'8px',zIndex:9999});
        document.body.appendChild(panel);
    }
    renderQuickGamePanel();
}

function renderQuickGamePanel(){
    const p = quickGameState;
    const panel = document.getElementById('quickGamePanel'); if(!panel) return;
    const currentPlayerName = p.players[p.currentPlayer];
    const qlist = p.currentPlayer === 0 ? p.qA : p.qB;
    const qObj = qlist[p.currentIndex];

    panel.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px"><strong>Juego rápido — ${escapeHtml(p.team)}</strong><button id="quickClose" style="background:transparent;color:#9bd;">✖</button></div>
        <div style="margin-bottom:8px">Turno: <strong>${escapeHtml(currentPlayerName)}</strong> — Pregunta ${p.currentIndex+1}/${qlist.length}</div>
        <div style="background:#051018;padding:8px;border-radius:6px;margin-bottom:8px"><div style="font-weight:700">${escapeHtml(qObj.name)}</div></div>
        <div style="display:flex;gap:8px;margin-bottom:8px"><input id="quickAnswerInput" placeholder="Escribe la respuesta" style="flex:1;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:#00101a;color:#dff"/></div>
        <div style="display:flex;gap:8px;justify-content:flex-end"><button id="quickSubmit">Enviar</button><button id="quickSkip">Saltar</button></div>
        <hr style="margin:10px 0;border-color:rgba(255,255,255,0.03)"/>
        <div style="font-size:13px;color:#9bd">Puntos — ${escapeHtml(p.players[0])}: <strong id="scoreA">${p.scoreA}</strong> • ${escapeHtml(p.players[1])}: <strong id="scoreB">${p.scoreB}</strong></div>
        <div style="margin-top:8px;font-size:12px;color:#8aa">(Respuestas repetidas son marcadas con X y no puntúan)</div>
    `;

    document.getElementById('quickClose').addEventListener('click', ()=>{ document.getElementById('quickGamePanel').remove(); quickGameState=null; sendMessage({type:'quick_game_end', payload:{aborted:true}}); });
    document.getElementById('quickSubmit').addEventListener('click', onQuickSubmit);
    document.getElementById('quickSkip').addEventListener('click', onQuickSkip);
    const inp = document.getElementById('quickAnswerInput'); if(inp) inp.focus();
}

function onQuickSkip(){
    // treat as 0 points and advance
    advanceQuickGame(null, 0, false, true);
}

function normalizeAnswer(s){ return (s||'').trim().toLowerCase(); }

function findMatchPoints(qObj, submitted){
    if(!qObj || !Array.isArray(qObj.answers)) return 0;
    const norm = normalizeAnswer(submitted);
    for(const a of qObj.answers){
        if((a.text||'').trim().toLowerCase() === norm){
            return Number(a.count) || 0;
        }
    }
    return 0;
}

function onQuickSubmit(){
    const inp = document.getElementById('quickAnswerInput'); if(!inp) return; const val = inp.value || '';
    const norm = normalizeAnswer(val);
    const p = quickGameState; if(!p) return;
    if(!norm){ alert('Escribe una respuesta'); return; }

    // detect repeats across both players answers
    const allSubmitted = p.answersA.concat(p.answersB).map(x=> normalizeAnswer(x.text));
    const isRepeat = allSubmitted.includes(norm);
    if(isRepeat){
        // play X sound and mark as repeated
        playXSound();
        advanceQuickGame(val, 0, true, false);
        return;
    }

    // get current question object
    const qlist = p.currentPlayer === 0 ? p.qA : p.qB;
    const qObj = qlist[p.currentIndex];
    const pts = findMatchPoints(qObj, val);
    advanceQuickGame(val, pts, false, false);
}

function advanceQuickGame(submittedText, pointsAwarded, isRepeat, skipped){
    const p = quickGameState; if(!p) return;
    const entry = {text: submittedText || '', points: Number(pointsAwarded)||0, repeat: !!isRepeat, skipped: !!skipped};
    if(p.currentPlayer === 0) { p.answersA.push(entry); p.scoreA += entry.points; }
    else { p.answersB.push(entry); p.scoreB += entry.points; }

    // inform board of update
    sendMessage({type:'quick_game_update', payload:{team:p.team, playerIndex:p.currentPlayer, qIndex:p.currentIndex, answer: entry.text, points: entry.points, repeat: entry.repeat}});

    // advance index or player
    const qlist = p.currentPlayer === 0 ? p.qA : p.qB;
    p.currentIndex++;
    if(p.currentIndex >= qlist.length){
        // switch to next player or finish
        if(p.currentPlayer === 0){ p.currentPlayer = 1; p.currentIndex = 0; }
        else { finishQuickGame(); return; }
    }

    renderQuickGamePanel();
}

function finishQuickGame(){
    const p = quickGameState; if(!p) return;
    const winner = p.scoreA > p.scoreB ? {player: p.players[0], score:p.scoreA} : p.scoreB > p.scoreA ? {player: p.players[1], score:p.scoreB} : null;
    let msg = `Resultados - ${p.players[0]}: ${p.scoreA} · ${p.players[1]}: ${p.scoreB}`;
    if(winner) msg += `\nGanador: ${winner.player} (${winner.score} pts)`; else msg += '\nEmpate';
    alert(msg);

    // send final message to board
    sendMessage({type:'quick_game_end', payload:{team:p.team, scores:{[p.players[0]]:p.scoreA, [p.players[1]]:p.scoreB}, winner: winner ? winner.player : null}});

    // remove panel
    const panel = document.getElementById('quickGamePanel'); if(panel) panel.remove(); quickGameState = null;
}

// wire start button
if(quickGameBtn){ quickGameBtn.addEventListener('click', startQuickGame); }
// Fast Money mode button
const fastMoneyBtn = document.getElementById('fastMoneyBtn');
if(fastMoneyBtn){ 
    fastMoneyBtn.addEventListener('click', ()=>{
        if(!fastMoneyBtn.disabled) {
            // Abrir controlador de Dinero Rápido
            showFastMoneyController();
        }
    });
}

// Inicializar conexión cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initializeConnection);
// También llamar inmediatamente por si el DOM ya está listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeConnection);
} else {
    initializeConnection();
}

</script>

<!-- Terminal de estado en la parte inferior -->
<div id="terminalStatus" style="position:fixed;bottom:0;left:0;right:0;background:#0a0a0a;color:#00ff00;font-family:'Fira Code','Courier New',monospace;font-size:11px;padding:6px 12px;border-top:1px solid #333;z-index:1000;display:flex;align-items:center;gap:8px;">
    <span style="color:#66fcf1;">➜</span>
    <span style="color:#ffffff;">game-controller@1100100devs:</span>
    <span style="color:#66fcf1;">~$</span>
    <span id="connType" style="color:#00ff00;">conectando...</span>
    <span id="terminalCursor" style="color:#00ff00;animation:blink 1s infinite;">▋</span>
</div>

<!-- Fast Money Controller Interface -->
<div id="fastMoneyController" class="fast-money-controller" style="display:none;">
    <div style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;">
        <div style="background:var(--bg);border:2px solid var(--accent);border-radius:12px;padding:30px;max-width:600px;width:90%;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h2 style="color:var(--accent);margin:0;">💰 DINERO RÁPIDO</h2>
                <div style="display:flex;align-items:center;gap:15px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="color:var(--text);font-size:14px;">META:</span>
                        <input id="fastMoneyTarget" type="number" value="200" min="50" max="500" step="25" 
                               style="width:70px;padding:4px;border:1px solid var(--accent);border-radius:4px;background:var(--bg);color:var(--text);text-align:center;font-weight:bold;">
                    </div>
                    <span style="color:#ffd700;font-weight:bold;font-size:18px;">
                        PUNTOS: <span id="fastControlScore">0</span>/<span id="fastControlTarget">200</span>
                    </span>
                    <button id="fastControlExit" style="background:var(--muted);color:white;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">× Cerrar</button>
                </div>
            </div>
            
            <div style="margin-bottom:20px;">
                <select id="questionSelect" style="width:70%;padding:10px;margin-right:10px;border:1px solid var(--accent);border-radius:6px;background:var(--bg);color:var(--text);">
                    <option value="0">1. Lenguaje de programación popular</option>
                    <option value="1">2. Navegador web</option>
                    <option value="2">3. Red social</option>
                    <option value="3">4. Sistema operativo</option>
                    <option value="4">5. Empresa de tecnología</option>
                </select>
                <button id="loadQuestion" style="background:var(--accent);color:var(--bg);border:none;padding:10px 15px;border-radius:6px;cursor:pointer;font-weight:bold;">📤 Cargar</button>
            </div>
            
            <div style="display:grid;gap:10px;margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:rgba(102,252,241,0.1);border-radius:6px;" data-answer="0">
                    <span style="font-weight:bold;color:var(--accent);min-width:25px;">1.</span>
                    <input type="text" id="answer1" readonly style="flex:1;border:none;background:transparent;color:var(--text);padding:5px;" placeholder="Respuesta 1">
                    <span id="points1" style="color:#ffd700;font-weight:bold;min-width:40px;">--</span>
                    <button id="reveal1" disabled style="background:var(--accent2);color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:12px;">Revelar</button>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:rgba(102,252,241,0.1);border-radius:6px;" data-answer="1">
                    <span style="font-weight:bold;color:var(--accent);min-width:25px;">2.</span>
                    <input type="text" id="answer2" readonly style="flex:1;border:none;background:transparent;color:var(--text);padding:5px;" placeholder="Respuesta 2">
                    <span id="points2" style="color:#ffd700;font-weight:bold;min-width:40px;">--</span>
                    <button id="reveal2" disabled style="background:var(--accent2);color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:12px;">Revelar</button>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:rgba(102,252,241,0.1);border-radius:6px;" data-answer="2">
                    <span style="font-weight:bold;color:var(--accent);min-width:25px;">3.</span>
                    <input type="text" id="answer3" readonly style="flex:1;border:none;background:transparent;color:var(--text);padding:5px;" placeholder="Respuesta 3">
                    <span id="points3" style="color:#ffd700;font-weight:bold;min-width:40px;">--</span>
                    <button id="reveal3" disabled style="background:var(--accent2);color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:12px;">Revelar</button>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:rgba(102,252,241,0.1);border-radius:6px;" data-answer="3">
                    <span style="font-weight:bold;color:var(--accent);min-width:25px;">4.</span>
                    <input type="text" id="answer4" readonly style="flex:1;border:none;background:transparent;color:var(--text);padding:5px;" placeholder="Respuesta 4">
                    <span id="points4" style="color:#ffd700;font-weight:bold;min-width:40px;">--</span>
                    <button id="reveal4" disabled style="background:var(--accent2);color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:12px;">Revelar</button>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:rgba(102,252,241,0.1);border-radius:6px;" data-answer="4">
                    <span style="font-weight:bold;color:var(--accent);min-width:25px;">5.</span>
                    <input type="text" id="answer5" readonly style="flex:1;border:none;background:transparent;color:var(--text);padding:5px;" placeholder="Respuesta 5">
                    <span id="points5" style="color:#ffd700;font-weight:bold;min-width:40px;">--</span>
                    <button id="reveal5" disabled style="background:var(--accent2);color:white;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:12px;">Revelar</button>
                </div>
            </div>
            
            <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                <button id="nextQuestionBtn" disabled style="background:var(--accent);color:var(--bg);border:none;padding:10px 15px;border-radius:6px;cursor:pointer;font-weight:bold;">▶️ Siguiente</button>
                <button id="duplicateAnswerBtn" style="background:#ff6b35;color:white;border:none;padding:10px 15px;border-radius:6px;cursor:pointer;font-weight:bold;">🚫 Ya Mencionada</button>
                <button id="revealPointsBtn" style="background:#f59e0b;color:white;border:none;padding:10px 15px;border-radius:6px;cursor:pointer;font-weight:bold;">💰 Revelar Puntos</button>
                <button id="resetFastMoney" style="background:#ef4444;color:white;border:none;padding:10px 15px;border-radius:6px;cursor:pointer;font-weight:bold;">🔄 Reiniciar</button>
                <button id="finishFastMoney" style="background:#10b981;color:white;border:none;padding:10px 15px;border-radius:6px;cursor:pointer;font-weight:bold;">🏆 Finalizar</button>
            </div>
            
            <div id="fastControlStatus" style="text-align:center;margin-top:15px;color:var(--muted);font-size:14px;">¡Listo para comenzar!</div>
        </div>
    </div>
</div>

</body>
</html>