<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controlador - 100 Prgm   Dijeron</title>
    <style>
        /* Modern controller design */
        :root{
            --bg-dark:#0a0e27;
            --bg-card:#131829;
            --accent:#00d9ff;
            --accent-hover:#00b8d9;
            --success:#10b981;
            --danger:#ef4444;
            --text-light:#e2e8f0;
            --text-muted:#94a3b8;
            --border:#1e293b;
        }
        
        *{box-sizing:border-box;margin:0;padding:0}
        
        body{
            font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial;
            background:linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color:var(--text-light);
            min-height:100vh;
            padding:20px;
        }
        
        header{
            background:var(--bg-card);
            padding:20px 30px;
            border-radius:12px;
            margin-bottom:24px;
            box-shadow:0 4px 24px rgba(0,0,0,0.4);
            border:1px solid var(--border);
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        
        h1{
            font-size:28px;
            font-weight:800;
            color:var(--accent);
            letter-spacing:0.5px;
        }
        
        #state{
            color:var(--success);
            font-weight:600;
        }
        
        section{
            background:var(--bg-card);
            padding:20px;
            border-radius:12px;
            margin-bottom:20px;
            box-shadow:0 4px 24px rgba(0,0,0,0.4);
            border:1px solid var(--border);
        }
        
        h3{
            color:var(--accent);
            margin-bottom:16px;
            font-size:18px;
            font-weight:700;
        }
        
        label{
            display:inline-block;
            color:var(--text-muted);
            font-size:13px;
            font-weight:600;
            margin-bottom:6px;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }
        
        input[type="text"], input[type="number"], select{
            background:rgba(255,255,255,0.05);
            border:1px solid var(--border);
            color:var(--text-light);
            padding:10px 14px;
            border-radius:8px;
            font-size:14px;
            transition:all 0.2s;
        }
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus{
            outline:none;
            border-color:var(--accent);
            box-shadow:0 0 0 3px rgba(0,217,255,0.1);
        }
        
        button{
            background:linear-gradient(135deg, var(--accent) 0%, #0099cc 100%);
            color:#fff;
            border:none;
            padding:8px 16px;
            border-radius:6px;
            font-weight:600;
            font-size:13px;
            cursor:pointer;
            transition:all 0.2s;
            margin-left:8px;
            box-shadow:0 2px 8px rgba(0,217,255,0.3);
        }
        
        button:hover{
            transform:translateY(-2px);
            box-shadow:0 4px 12px rgba(0,217,255,0.4);
        }
        
        button:active{
            transform:translateY(0);
        }
        
        button:disabled{
            background:linear-gradient(135deg, #334155 0%, #1e293b 100%);
            cursor:not-allowed;
            opacity:0.5;
            box-shadow:none;
        }
        
        #roundAssign{
            background:rgba(16,185,129,0.1);
            border:1px solid rgba(16,185,129,0.3);
        }
        
        #roundAssign button{
            background:linear-gradient(135deg, var(--success) 0%, #059669 100%);
            box-shadow:0 2px 8px rgba(16,185,129,0.3);
        }
        
        #teamScoresDisplay{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }
        
        #teamScoresDisplay > div{
            background:rgba(0,217,255,0.05);
            border:1px solid rgba(0,217,255,0.2);
            padding:16px;
            border-radius:10px;
            min-width:140px;
            text-align:center;
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
            border-radius:10px;
            background:rgba(255,255,255,0.03);
            border:1px solid var(--border);
            transition:all 0.2s;
        }
        
        .answer:hover{
            background:rgba(255,255,255,0.05);
            border-color:rgba(0,217,255,0.3);
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
        }
    </style>
</head>
<body>
<header>
    <h1>Controlador</h1>
    <div>Estado: <span id="state">Listo</span></div>
</header>
<main>
    <p>Define las respuestas y luego usa los botones para revelarlas en el tablero.</p>

    <section>
        <label>Pregunta:</label>
        <input id="question" style="width:60%" placeholder="¿Qué fruta comen más los niños?" />
    </section>

    <section style="margin-top:12px">
        <button id="setDefaults">Cargar ejemplo</button>
        <button id="sendInit">Enviar al tablero</button>
        <button id="resend">Reenviar estado</button>
        <button id="reset">Reset</button>
        <button id="addAnswer">Agregar respuesta</button>
    </section>
    
    <section style="margin-top:12px">
        <h3>Ronda</h3>
        <label>Puntos de la ronda:</label>
        <input id="roundPoints" type="number" value="10" style="width:100px;margin-right:8px" />
        <label>Equipos (coma-separated):</label>
        <input id="teamsInput" placeholder="Familia A,Familia B" style="width:280px;margin-left:8px" />
        <div style="margin-top:8px">
            <button id="startRound">Iniciar ronda</button>
            <button id="addStrike" style="margin-left:10px;background:#ef4444;color:white;">❌ X</button>
            <span id="strikeCount" style="margin-left:10px;font-size:14px;font-weight:bold;color:#ef4444;">X: 0/3</span>
        </div>
        <div id="roundAssign" style="display:none;margin-top:8px;padding:8px;border-radius:6px;background:#f1f5f9">
            <div id="roundReadyText" style="font-weight:700;color:#0b1220;margin-bottom:6px">La ronda terminó. ¿Qué familia gana los puntos?</div>
            <div id="roundTeamButtons" style="display:flex;gap:8px;flex-wrap:wrap"></div>
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
// logs removed by user request
const ctrlLogs = null;

 

let answers = [];
let teamScores = {}; // persisted per browser
let currentRound = {points:0, teams:[], accumulatedPoints:0};
let strikeCount = 0; // Counter for X's (wrong answers)

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

document.getElementById('setDefaults').addEventListener('click', () => {
    questionEl.value = '¿Qué fruta comen más los niños?';
    answers = [
        {text:'Manzana', count:32, revealed:false},
        {text:'Plátano', count:25, revealed:false},
        {text:'Naranja', count:18, revealed:false},
        {text:'Fresa', count:12, revealed:false},
        {text:'Uva', count:8, revealed:false}
    ];
    render();
});

document.getElementById('addAnswer').addEventListener('click', ()=>{
    answers.push({text:'Nueva respuesta', count:0, revealed:false});
    render();
});

// round controls
const roundPointsEl = document.getElementById('roundPoints');
const teamsInput = document.getElementById('teamsInput');
const startRoundBtn = document.getElementById('startRound');
const roundAssignEl = document.getElementById('roundAssign');
const roundTeamButtons = document.getElementById('roundTeamButtons');

startRoundBtn.addEventListener('click', ()=>{
    const pts = Number(roundPointsEl.value)||0;
    const teamNames = (teamsInput.value||'').split(',').map(s=>s.trim()).filter(Boolean);
    if(teamNames.length === 0){ alert('Añade al menos un equipo'); return; }
    
    // disable button during countdown
    startRoundBtn.disabled = true;
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
            
            // send round points
            sendMessage({type:'round_points', payload:{points:pts, teams: teamNames}});
            
            // hide assignment area until board signals round_ready
            roundAssignEl.style.display = 'none';
            
            // ensure teams exist in local scores (keep existing points)
            teamNames.forEach(t=>{ if(!(t in teamScores)) teamScores[t] = 0; });
            persistTeamScores();
            renderTeamScores();
            updateTeamSelect();
            
            stateEl.textContent = 'Nueva ronda';
        }
    }, 1000);
});


function showAssignIfRoundComplete(){
    const realAnswers = answers.filter(a=>a && a.text);
    const allRevealed = realAnswers.length>0 && realAnswers.every(a=>a.revealed);
    if(allRevealed && currentRound && currentRound.points){
        // Use accumulated points from correct answers (real game logic)
        const finalPoints = currentRound.accumulatedPoints || 0;
        
        roundTeamButtons.innerHTML = '';
        // Show total points that will be awarded
        const readyText = document.getElementById('roundReadyText');
        if(readyText) readyText.textContent = `La ronda terminó. Asignar ${finalPoints} puntos a:`;
        
        (currentRound.teams || []).forEach(t=>{
            const b = document.createElement('button'); b.textContent = t; b.addEventListener('click', ()=>{
                // Award ALL accumulated points to winning team
                sendMessage({type:'assign_points', payload:{team:t, points: finalPoints}});
                if(!(t in teamScores)) teamScores[t]=0;
                teamScores[t] = Number(teamScores[t]||0) + finalPoints;
                persistTeamScores();
                renderTeamScores();
                roundAssignEl.style.display = 'none';
                // Reset round
                currentRound = {points:0, teams:[], accumulatedPoints:0};
            });
            roundTeamButtons.appendChild(b);
        });
        roundAssignEl.style.display = 'block';
    }
}

// add a team selector to choose which team receives points on 'Acierto'
const teamSelect = document.createElement('select'); teamSelect.id = 'teamSelect'; teamSelect.style.marginLeft = '8px';
startRoundBtn.parentNode.appendChild(teamSelect);
function updateTeamSelect(){
    teamSelect.innerHTML = '';
    (Object.keys(teamScores).length?Object.keys(teamScores):['Familia A','Familia B']).forEach(t=>{
        const o = document.createElement('option'); o.value = t; o.textContent = t; teamSelect.appendChild(o);
    });
}
updateTeamSelect();


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
    teamScores = {};
    
    // Clear localStorage first
    localStorage.removeItem('game-team-scores');
    
    // Update displays
    updateStrikeDisplay();
    renderTeamScores();
    render();
    
    // Send comprehensive reset to board
    sendMessage({type:'reset_all', payload:{}});
    
    stateEl.textContent = 'Listo';
    
    // Hide assignment area
    const roundAssignEl = document.getElementById('roundAssign');
    if(roundAssignEl) roundAssignEl.style.display = 'none';
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
            if(allRevealed && currentRound && currentRound.points){
                // show assign buttons populated with currentRound.teams
                roundTeamButtons.innerHTML = '';
                (currentRound.teams || []).forEach(t=>{
                    const b = document.createElement('button'); b.textContent = t; b.addEventListener('click', ()=>{
                        sendMessage({type:'assign_points', payload:{team:t, points: currentRound.points}});
                        // update local scores too
                        if(!(t in teamScores)) teamScores[t]=0;
                        teamScores[t] = Number(teamScores[t]||0) + Number(currentRound.points||0);
                        persistTeamScores();
                        renderTeamScores();
                        roundAssignEl.style.display = 'none';
                        // clear current round
                        currentRound = {points:0, teams:[]};
                    });
                    roundTeamButtons.appendChild(b);
                });
                roundAssignEl.style.display = 'block';
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

    // auto-resend when answers change, to help boards that miss initial message
    let autoResendTimer = null;
    // auto-resend when answers change, to help boards that miss initial message
    function scheduleAutoResend(){ if(autoResendTimer) clearTimeout(autoResendTimer); autoResendTimer = setTimeout(()=>{ const payload = {answers, state:stateEl.textContent, question:questionEl.value}; sendMessage({type:'init', payload}); }, 800); }
    
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
        if(strikeCount < 3){
            strikeCount++;
        } else {
            // Reset to 1 if already at 3
            strikeCount = 1;
        }
        updateStrikeDisplay();
        sendMessage({type:'update_strikes', payload:{count: strikeCount}});
    });
}

render();
updateStrikeDisplay();

// persist & render helpers for controller
function persistTeamScores(){ try{ localStorage.setItem('game-team-scores', JSON.stringify(teamScores)); }catch(e){} }
function renderTeamScores(){ const el = document.getElementById('teamScoresDisplay'); if(!el) return; el.innerHTML = ''; if(Object.keys(teamScores).length===0) { teamScores = {'Familia A':0,'Familia B':0}; }
    Object.keys(teamScores).forEach(name=>{ const d = document.createElement('div'); d.style.padding='8px'; d.style.border='1px solid #e2e8f0'; d.style.borderRadius='6px'; d.style.minWidth='120px'; d.innerHTML = `<div style='font-size:12px;color:#475569'>${escapeHtml(name)}</div><div style='font-weight:800;font-size:18px'>${String(teamScores[name]||0).padStart(3,'0')}</div>`; el.appendChild(d); }); }

// load persisted scores
try{ const s = localStorage.getItem('game-team-scores'); if(s) teamScores = JSON.parse(s) || {}; }catch(e){}
renderTeamScores();

// small helper to avoid XSS in injected text (same as board)
function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

// attempt to pre-load answers from storage if present to have a fast start
try{ const st = localStorage.getItem('game-100mx'); if(st){ const parsed = JSON.parse(st); if(parsed && parsed.msg && parsed.msg.payload && Array.isArray(parsed.msg.payload.answers)){ answers = parsed.msg.payload.answers.map(a=>({...a})); render(); } } }catch(e){}
</script>
</body>
</html>