<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controller - 1100100 Devs Dijeron</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Terminal / Developer aesthetic */
        @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700;900&display=swap');
        
        :root{
            --bg:#0b0c10;
            --bg-dark:#000000;
            --glass:rgba(102,252,241,0.03);
            --accent:#66fcf1;
            --accent2:#45a29e;
            --text:#c5c6c7;
            --muted:#7a8084;
            --success:#10b981;
            --danger:#ef4444;
        }
        
        *{box-sizing:border-box;margin:0;padding:0}
        
        body{
            font-family:'Fira Code', monospace;
            background:linear-gradient(180deg, #000000 0%, #0b0c10 40%, #1f2833 100%);
            color:var(--text);
            min-height:100vh;
            padding:20px;
        }
        
        header{
            background:rgba(11,12,16,0.85);
            padding:20px 30px;
            border-radius:8px;
            margin-bottom:24px;
            backdrop-filter: blur(10px);
            box-shadow:0 0 40px rgba(102,252,241,0.1), 0 0 80px rgba(69,162,158,0.05), inset 0 1px 0 rgba(102,252,241,0.05);
            border:1px solid rgba(102,252,241,0.2);
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        
        h1{
            font-size:28px;
            font-weight:900;
            color:var(--accent);
            letter-spacing:1px;
            text-shadow:0 0 20px rgba(102,252,241,0.6), 0 0 40px rgba(102,252,241,0.3);
        }
        
        #state{
            color:var(--success);
            font-weight:600;
            text-shadow:0 0 10px rgba(16,185,129,0.5);
        }
        
        section{
            background:rgba(11,12,16,0.85);
            padding:20px;
            border-radius:8px;
            margin-bottom:20px;
            backdrop-filter: blur(10px);
            box-shadow:0 0 40px rgba(102,252,241,0.1), 0 0 80px rgba(69,162,158,0.05), inset 0 1px 0 rgba(102,252,241,0.05);
            border:1px solid rgba(102,252,241,0.2);
        }
        
        h3{
            color:var(--accent);
            margin-bottom:16px;
            font-size:18px;
            font-weight:700;
            text-shadow:0 0 15px rgba(102,252,241,0.4);
        }
        
        label{
            display:inline-block;
            color:var(--muted);
            font-size:13px;
            font-weight:600;
            margin-bottom:6px;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }
        
        input[type="text"], input[type="number"], select{
            background:rgba(0,0,0,0.5);
            border:1px solid rgba(102,252,241,0.3);
            color:var(--text);
            padding:10px 14px;
            border-radius:6px;
            font-size:14px;
            font-family:'Fira Code', monospace;
            transition:all 0.2s;
        }
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus{
            outline:none;
            border-color:var(--accent);
            box-shadow:0 0 20px rgba(102,252,241,0.15), inset 0 0 20px rgba(102,252,241,0.05);
        }
        
        button{
            background:linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            color:#0b0c10;
            border:none;
            padding:8px 16px;
            border-radius:6px;
            font-weight:700;
            font-size:13px;
            cursor:pointer;
            transition:all 0.2s;
            margin-left:8px;
            box-shadow:0 0 15px rgba(102,252,241,0.3);
            font-family:'Fira Code', monospace;
        }
        
        button:hover{
            transform:translateY(-2px);
            box-shadow:0 0 25px rgba(102,252,241,0.5);
        }
        
        button:active{
            transform:translateY(0);
        }
        
        button:disabled{
            background:rgba(122,128,132,0.3);
            cursor:not-allowed;
            opacity:0.5;
            box-shadow:none;
        }

        /* Multiplier buttons */
        .multiplier-btn{
            background:rgba(0,0,0,0.5);
            border:2px solid rgba(102,252,241,0.3);
            color:var(--text);
            padding:8px 16px;
            border-radius:8px;
            font-weight:700;
            font-size:14px;
            cursor:pointer;
            transition:all 0.2s;
            margin-left:0;
            box-shadow:none;
        }
        
        .multiplier-btn:hover{
            border-color:var(--accent);
            color:var(--accent);
            box-shadow:0 0 15px rgba(102,252,241,0.2);
            transform:translateY(0);
        }
        
        .multiplier-btn.active{
            background:linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            border-color:var(--accent);
            color:#0b0c10;
            box-shadow:0 0 20px rgba(102,252,241,0.4);
        }

        /* Active team selector styling */
        #turnControls{margin-top:10px}
        .active-team-btn{
            background:rgba(0,0,0,0.5);
            border:1px solid rgba(102,252,241,0.3);
            color:var(--text);
            padding:8px 12px;
            border-radius:8px;
            cursor:pointer;
            font-weight:700;
            font-family:'Fira Code', monospace;
        }
        .active-team-btn.selected{
            background:linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            border-color:var(--accent);
            color:#0b0c10;
            box-shadow:0 0 20px rgba(102,252,241,0.4);
        }
        
        #roundAssign{
            background:rgba(16,185,129,0.1);
            border:1px solid rgba(16,185,129,0.3);
        }
        
        #roundAssign button{
            background:linear-gradient(135deg, var(--success) 0%, #059669 100%);
            box-shadow:0 0 15px rgba(16,185,129,0.3);
        }
        
        #teamScoresDisplay{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }
        
        #teamScoresDisplay > div{
            background:rgba(0,0,0,0.5);
            border:1px solid rgba(102,252,241,0.3);
            padding:16px;
            border-radius:8px;
            min-width:140px;
            text-align:center;
            box-shadow:0 0 20px rgba(102,252,241,0.1);
        }
        
        .answers{
            display:flex;
            flex-direction:column;
            gap:10px;
            max-width:100%;
        }
        
        .answer{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:16px;
            border-radius:8px;
            background:rgba(0,0,0,0.5);
            border:1px solid rgba(102,252,241,0.2);
            transition:all 0.2s;
        }
        
        .answer:hover{
            background:rgba(0,0,0,0.7);
            border-color:rgba(102,252,241,0.4);
            box-shadow:0 0 15px rgba(102,252,241,0.1);
        }
        
        .answer.answer-correct{
            background:rgba(16,185,129,0.1);
            border:1px solid rgba(16,185,129,0.4);
            box-shadow:0 0 20px rgba(16,185,129,0.2);
        }
        
        .answer input{
            margin-right:8px;
        }
        
        .answer strong{
            color:var(--accent);
            margin-right:12px;
            font-size:15px;
            text-shadow:0 0 10px rgba(102,252,241,0.3);
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
        <a href="/questions" style="margin-left:12px;color:var(--accent);text-decoration:none;font-weight:700">📚 Banco de Preguntas</a>
        <div style="margin-top:10px;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <label style="margin-bottom:0">Elegir desde banco:</label>
            <select id="questionDropdown" style="min-width:360px">
                <option value="">-- seleccionar pregunta activa --</option>

            </select>
            <button id="loadSelectedQuestion">Cargar</button>
            <button id="refreshQuestions" title="Actualizar lista" style="background:rgba(0,0,0,0.5);color:var(--text);border:1px solid rgba(102,252,241,0.3)">↻</button>
            <span id="dropdownInfo" style="font-size:12px;color:var(--muted)">Muestra solo preguntas activas</span>
            <span id="dropdownStatus" style="font-size:12px;color:#94a3b8"></span>
        </div>
    </section>

    <section style="margin-top:12px">
        <!-- <button id="setDefaults">Cargar ejemplo</button> -->
        <button id="sendInit">Enviar al tablero</button>
        <button id="resend">Reenviar estado</button>
        <button id="reset">Reset</button>
        <!-- <button id="addAnswer">Agregar respuesta</button> -->
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
            <button id="addStrike" style="margin-left:10px;background:#ef4444;color:white;">❌ X</button>
            <span id="strikeCount" style="margin-left:10px;font-size:14px;font-weight:bold;color:#ef4444;">X: 0/3</span>
            <span id="roundNumber" style="margin-left:10px;font-size:14px;font-weight:bold;color:var(--accent)">Ronda: 1</span>
        </div>
        <div id="turnControls" style="display:none">
            <label style="margin-right:8px">Turno:</label>
            <div id="activeTeamButtons" style="display:flex;gap:8px;flex-wrap:wrap"></div>
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
try {
    if (typeof BroadcastChannel !== 'undefined') {
        channel = new BroadcastChannel('game-100mx');
        usingBroadcast = true;
        console.debug('[controller] BroadcastChannel opened', channel);
    }
} catch(e){
    console.debug('[controller] BroadcastChannel not available', e);
}

// fallback send/receive via localStorage
function sendMessage(msg){
    if (usingBroadcast && channel) return channel.postMessage(msg);
    try{ localStorage.setItem('game-100mx', JSON.stringify({msg, ts:Date.now()})); }catch(e){console.debug('[controller] localStorage send failed', e)}
}

function addStorageListener(fn){
    window.addEventListener('storage', (ev)=>{
        if (!ev.key) return;
        if (ev.key !== 'game-100mx') return;
        try{ const data = JSON.parse(ev.newValue); fn(data.msg); }catch(e){}
    });
}

// if BroadcastChannel present, wire up its onmessage; otherwise use storage listener
if (usingBroadcast && channel){
    channel.onmessage = (ev)=>{ console.debug('[controller] received (bc)', ev.data); handleIncoming(ev.data); };
} else {
    addStorageListener((data)=>{ console.debug('[controller] received (storage)', data); handleIncoming(data); });
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

// ===== Dropdown: fetch and load active questions (global) =====
async function fetchQuestionsForDropdown(){
    if(!questionDropdownEl) return;
    try{
        if(dropdownStatusEl) dropdownStatusEl.textContent = 'Cargando…';
        const res = await fetch(API_QUESTIONS_BASE);
        const data = await res.json();
        const active = Array.isArray(data) ? data.filter(q => q && q.is_active) : [];
        active.sort((a,b)=>{ const an=(a.name||'').toLowerCase(); const bn=(b.name||'').toLowerCase(); return an<bn?-1:an>bn?1:0; });
        dropdownQuestionsIndex = active;
        questionDropdownEl.innerHTML = '<option value="">-- seleccionar pregunta activa --</option>';
        active.forEach(q => {
            const cat = q.category === 'dev' ? 'Desarrollo' : 'General';
            const used = q.times_used || 0;
            const opt = document.createElement('option');
            opt.value = String(q.id);
            opt.textContent = `${q.name} · ${cat} · usados: ${used}`;
            questionDropdownEl.appendChild(opt);
        });
        if(dropdownStatusEl){
            dropdownStatusEl.style.color = active.length ? '#22c55e' : '#f59e0b';
            dropdownStatusEl.textContent = active.length ? `${active.length} activa(s)` : 'No hay preguntas activas';
        }
    }catch(e){
        console.error('Error fetching questions for dropdown', e);
        if(dropdownStatusEl){ dropdownStatusEl.style.color = '#ef4444'; dropdownStatusEl.textContent = 'Error cargando lista'; }
    }
}

async function loadSelectedQuestion(){
    if(!questionDropdownEl) return;
    const id = questionDropdownEl.value;
    if(!id){ alert('Selecciona una pregunta'); return; }
    try{
        const res = await fetch(`${API_QUESTIONS_BASE}/${id}/load`);
        const payload = await res.json();
        if(!payload || !payload.success){ alert('No se pudo cargar la pregunta'); return; }
        questionEl.value = payload.question || '';
        answers = (payload.answers || []).map(a => ({text:a.text, count:a.count, revealed:false, correct:false}));
        answers.sort((a,b)=> b.count - a.count);
        render();
        const initPayload = {answers, state:'Pregunta cargada', question: questionEl.value};
        sendMessage({type:'init', payload: initPayload});
        stateEl.textContent = 'Pregunta cargada';
    }catch(e){
        console.error('Error loading selected question', e);
        alert('Ocurrió un error al cargar la pregunta');
    }
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
                    <input data-idx="${i}" value="${a.text}" style="width:70%" />
                    <input data-idx-count="${i}" value="${a.count}" style="width:60px;margin-left:8px" />
                </div>
                <div>
                    <button data-action="reveal" data-idx="${i}">Revelar</button>
                    <button data-action="correct" data-idx="${i}" title="Marcar como acertada" ${a.correct ? 'disabled' : ''}>Acierto</button>
                    <button data-action="hide" data-idx="${i}">Ocultar</button>
                    <button data-action="remove" data-idx="${i}">Eliminar</button>
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
        sendMessage({type:'reveal', payload:{index:idx}});
        answers[idx].revealed = true;
        showAssignIfRoundComplete();
    } else if (action === 'correct') {
        // prevent double scoring: if already marked correct, ignore
        if(answers[idx] && answers[idx].correct) { return; }
        
        const pts = Number(answers[idx] && answers[idx].count) || 0;
        
        // reveal on board
        sendMessage({type:'reveal', payload:{index:idx}});
        answers[idx].revealed = true;
        answers[idx].correct = true;
        
        // Add points to ROUND total (not to team yet!)
        if(!currentRound.accumulatedPoints) currentRound.accumulatedPoints = 0;
        currentRound.accumulatedPoints += pts;
        
        // Update round points display
        sendMessage({type:'update_round_total', payload:{points: currentRound.accumulatedPoints}});
        
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
        });
    }
});

startRoundBtn.addEventListener('click', ()=>{
    const teamNames = getTeamNames();
    if(teamNames.length !== 2){
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
    runRound(teamNames, /*keepScores*/ true);
});

// Finish round - reveal all unrevealed answers without adding points
finishRoundBtn.addEventListener('click', ()=>{
    if(!currentRound || !Array.isArray(currentRound.answers)) {
        alert('⚠️ No hay ronda activa');
        return;
    }
    
    // Hide steal attempt UI if active
    if(stealAttemptEl && stealAttemptEl.style.display !== 'none') {
        console.log('⚠️ Cancelando robo de puntos activo');
        stealAttemptEl.style.display = 'none';
    }
    
    // Check if there are any unrevealed answers
    const unrevealedAnswers = currentRound.answers.filter(ans => !ans.revealed);
    if(unrevealedAnswers.length === 0) {
        alert('⚠️ Todas las respuestas ya están reveladas');
        return;
    }
    
    console.log('🏁 Finalizando ronda - revelando', unrevealedAnswers.length, 'respuestas');
    
    // Reveal all unrevealed answers WITHOUT adding to accumulated points
    unrevealedAnswers.forEach((ans) => {
        ans.revealed = true;
        console.log('Revelando:', ans.text, '-', ans.count, 'puntos');
    });
    
    // Update UI and send to board
    render();
    sendMessage({type:'state', payload:{question, answers: currentRound.answers}});
    
    console.log('✅ Todas las respuestas reveladas. Puntos acumulados:', currentRound.accumulatedPoints || 0);
});

// Common routine to transition to a new round
function runRound(teamNames, keepScores){
    // disable button during countdown
    startRoundBtn.disabled = true;
    nextRoundBtn.disabled = true;
    const originalText = startRoundBtn.textContent;
    
    // countdown from 5 to 1
    let countdown = 5;
    startRoundBtn.textContent = `Iniciando en ${countdown}...`;
    
    // send countdown to board
    sendMessage({type:'countdown', payload:{count: countdown}});
    
    const countdownInterval = setInterval(()=>{
        countdown--;
        if(countdown > 0){
            startRoundBtn.textContent = `Iniciando en ${countdown}...`;
            sendMessage({type:'countdown', payload:{count: countdown}});
        } else {
            clearInterval(countdownInterval);
            startRoundBtn.textContent = originalText;
            startRoundBtn.disabled = false;
            nextRoundBtn.disabled = false;
            
            // hide countdown on board
            sendMessage({type:'countdown', payload:{count: 0}});
            
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
            
            // announce teams and reset round points to 0 (round points accumulate automatically on aciertos)
            sendMessage({type:'round_points', payload:{points:0, teams: teamNames, roundNumber: roundNumber, multiplier: pointMultiplier}});
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
            
            stateEl.textContent = 'Nueva ronda';
        }
    }, 1000);
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
            sendMessage({type:'assign_points', payload:{team:activeTeam, points: finalPoints}});
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
                    sendMessage({type:'assign_points', payload:{team:t, points: finalPoints}});
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
}


document.getElementById('sendInit').addEventListener('click', () => {
    const payload = {answers, state:'En juego', question: questionEl.value};
    console.debug('[controller] sending init', payload);
    sendMessage({type:'init', payload});
    stateEl.textContent = 'En juego';
});
 

document.getElementById('resend').addEventListener('click', ()=>{
    const payload = {answers, state:stateEl.textContent, question: questionEl.value};
    console.debug('[controller] manual resend', payload);
    sendMessage({type:'init', payload});
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
    
    // Clear localStorage first
    localStorage.removeItem('game-team-scores');
    
    // Update displays
    updateStrikeDisplay();
    renderTeamScores();
    render();
    const roundNumEl = document.getElementById('roundNumber');
    if(roundNumEl) roundNumEl.textContent = 'Ronda: 1';
    
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

channel.onmessage = (ev) => {
    // centralized handler for incoming messages (from channel or storage)
    function handleIncoming(msg){
        if (!msg || !msg.type) return;
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
                        sendMessage({type:'assign_points', payload:{team:activeTeam, points: finalPoints}});
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
                                sendMessage({type:'assign_points', payload:{team:t, points: finalPoints}});
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
    }

    // logging disabled
    function logCtrl(text){}
    
    // Call handleIncoming on received messages
    handleIncoming(ev.data);
};

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
function renderTeamScores(){ const el = document.getElementById('teamScoresDisplay'); if(!el) return; el.innerHTML = '';
    Object.keys(teamScores).forEach(name=>{ const d = document.createElement('div'); d.style.padding='8px'; d.style.border='1px solid #e2e8f0'; d.style.borderRadius='6px'; d.style.minWidth='120px'; d.innerHTML = `<div style='font-size:12px;color:#475569'>${escapeHtml(name)}</div><div style='font-weight:800;font-size:18px'>${String(teamScores[name]||0).padStart(3,'0')}</div>`; el.appendChild(d); }); }

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
            sendMessage({type:'assign_points', payload:{team: stealTeam, points: points}});
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
            sendMessage({type:'assign_points', payload:{team: originalTeam, points: points}});
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
</script>
</body>
</html>