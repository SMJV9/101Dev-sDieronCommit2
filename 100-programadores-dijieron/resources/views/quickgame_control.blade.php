<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Control - Juego rápido</title>
    @vite('resources/css/controller.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body{background:#051018;color:#cfe;padding:18px;font-family:Inter,system-ui}
        .progress-wrap{background:linear-gradient(90deg,#052026,#001217);padding:6px;border-radius:8px;border:1px solid rgba(102,252,241,0.04);display:flex;align-items:center;gap:12px}
        .progress-bar{height:18px;background:#022;flex:1;border-radius:6px;overflow:hidden;border:1px solid rgba(255,255,255,0.03)}
        .progress-fill{height:100%;background:linear-gradient(90deg,#06b6d4,#10b981);width:0%}
        .panel{max-width:1100px;margin:0 auto}
        .questionCard{background:#001217;padding:14px;border-radius:8px;border:1px solid rgba(102,252,241,0.06);margin-bottom:10px}
        .controls{display:flex;gap:8px}
        button{padding:10px;border-radius:8px;border:0;background:linear-gradient(90deg,#06b6d4,#06b6d4);color:#012}
        .danger{background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff}
    </style>
</head>
<body>
<div class="panel">
    <h1>Control — Juego rápido</h1>
    <div style="display:flex;gap:12px;align-items:end;margin-bottom:12px">
        <div style="flex:1">
            <label>Equipo que participa:</label>
            <input id="teamInput" placeholder="Equipo 1" style="width:100%;padding:8px;border-radius:6px;background:#001019;color:#dff;border:1px solid rgba(255,255,255,0.03)" />
        </div>
        <div style="width:220px">
            <label>Jugador A (nombre):</label>
            <input id="nameA" placeholder="Jugador A" style="width:100%;padding:8px;border-radius:6px;background:#001019;color:#dff;border:1px solid rgba(255,255,255,0.03)" />
        </div>
        <div style="width:220px">
            <label>Jugador B (nombre):</label>
            <input id="nameB" placeholder="Jugador B" style="width:100%;padding:8px;border-radius:6px;background:#001019;color:#dff;border:1px solid rgba(255,255,255,0.03)" />
        </div>
        <div style="width:160px">
            <label>Puntos a:</label>
            <select id="pointsToSelect" style="width:100%;padding:8px;border-radius:6px;background:#001019;color:#dff;border:1px solid rgba(255,255,255,0.03)">
                <option value="A">Jugador A</option>
                <option value="B">Jugador B</option>
            </select>
        </div>
        <div style="width:160px">
            <label>Jugador activo:</label>
            <select id="activeSelect" style="width:100%;padding:8px;border-radius:6px;background:#001019;color:#dff;border:1px solid rgba(255,255,255,0.03)">
                <option value="0">Jugador A</option>
                <option value="1">Jugador B</option>
            </select>
        </div>
        <div style="width:180px">
            <label>Preguntas totales:</label>
            <input id="totalInput" type="number" value="5" min="1" max="20" style="width:100%" />
        </div>
        <div style="width:140px">
            <label>Segundos por turno:</label>
            <input id="secondsInput" type="number" value="60" min="10" max="300" style="width:100%" />
        </div>
        <div style="width:140px">
            <label>Puntaje objetivo:</label>
            <input id="targetInput" type="number" value="30" min="1" style="width:100%" />
        </div>
        <div>
            <button id="loadBtn">Cargar preguntas</button>
        </div>
        <div>
            <button id="startBtn" style="background:linear-gradient(90deg,#10b981,#059669);color:#012">Iniciar</button>
        </div>
        <div>
            <button id="pingBtn" style="background:linear-gradient(90deg,#06b6d4,#06b6d4);color:#012">Ping display</button>
        </div>
    </div>

    <div id="controlArea" style="display:none">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
            <div style="display:flex;flex-direction:column">
                <div><strong id="playerLabel">Jugador A</strong> — Pregunta <span id="qIndex">1</span>/<span id="qTotal">5</span></div>
                <div style="margin-top:4px">Tiempo restante: <strong style="font-size:16px" id="turnTimer">60</strong>s</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
                <div class="muted">Puntos: <span id="scoreA">0</span> • <span id="scoreB">0</span></div>
                <div style="display:flex;gap:10px;align-items:center">
                    <div>Puntos restante para objetivo:</div>
                    <div style="min-width:220px;max-width:360px;" class="progress-wrap">
                        <div style="width:100px;font-weight:700;color:#bff" id="targetDisplay">0</div>
                        <div class="progress-bar"><div id="progressFill" class="progress-fill"></div></div>
                    </div>
                </div>
                <div style="opacity:.9">Preguntas en cola: <span id="remainingCount">5</span></div>
            </div>
        </div>

        <div id="statusLine" style="margin-bottom:10px;color:#9bd;display:block">Listo para iniciar.</div>

        <div class="questionCard">
            <div id="questionText" style="font-weight:800;font-size:18px">(sin pregunta)</div>
            <div id="answersList" style="margin-top:10px;color:#9bd;font-size:14px">(respuestas del banco no mostradas al público)</div>
        </div>

        <div class="controls" style="margin-bottom:12px">
            <button id="btnPass" style="background:linear-gradient(90deg,#f59e0b,#f97316);">Pasar</button>
            <button id="btnRepeat" style="background:linear-gradient(90deg,#ef4444,#ef4444);color:#fff">Repetida (X)</button>
            <button id="btnReveal" style="background:linear-gradient(90deg,#6366f1,#4f46e5);color:#fff">Revelar (mostrar respuestas)</button>
            <button id="btnStartScoring" style="background:linear-gradient(90deg,#f97316,#fb923c);color:#012">Iniciar puntuación</button>
            <button id="btnNoPoint" style="background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff;display:none;margin-left:6px">No otorgar punto</button>
            <button id="btnFinishScoring" style="background:linear-gradient(90deg,#059669,#10b981);color:#012;display:none">Finalizar puntuación</button>
            <button id="btnNext" style="background:linear-gradient(90deg,#94a3b8,#94a3b8);color:#012">Siguiente</button>
            <button id="btnEnd" class="danger">Finalizar juego</button>
        </div>

        <!-- Log oculto por defecto: se mantiene para debugging pero no se muestra al host -->
        <div id="log" style="background:#001018;padding:10px;border-radius:6px;color:#9bd;max-height:220px;overflow:auto;display:none"></div>
    </div>
</div>

<script>
const API_QUESTIONS_BASE = `${window.location.origin}/api/questions`;
let control = null; // {team, questions:[], queue:[], idx, player, scores:{A,B}, timer, timerId, secondsPerTurn, target}
let bc = null; let usingBC = false;
try{ if(typeof BroadcastChannel !== 'undefined'){ bc = new BroadcastChannel('game-100mx'); usingBC = true; console.debug('bc open'); } }catch(e){ }
// Robust send: use BroadcastChannel (if available) AND append message to a localStorage queue
function pushToQueue(item){
    try{
        const raw = localStorage.getItem('game-100mx-queue');
        const arr = raw ? JSON.parse(raw) : [];
        arr.push(item);
        localStorage.setItem('game-100mx-queue', JSON.stringify(arr));
    }catch(e){ try{ localStorage.setItem('game-100mx-queue', JSON.stringify([item])); }catch(e){} }
}

function send(msg){
    try{ if(usingBC && bc){ try{ bc.postMessage(msg); }catch(e){} } }catch(e){}
    try{ const envelope = { msg: msg, ts: Date.now(), id: Math.floor(Math.random()*1000000) }; pushToQueue(envelope); localStorage.setItem('game-100mx', JSON.stringify({last:envelope, ts:Date.now()})); }catch(e){}
}

// Polling fallback on controller: read localStorage periodically to process incoming messages
let _lastSeenStorageTs_ctrl = 0;
function pollLocalStorageController(){ try{
        const raw = localStorage.getItem('game-100mx');
        if(!raw) return;
        const d = JSON.parse(raw);
        if(!d) return;
        const ts = Number(d.ts || 0);
        if(ts && ts > _lastSeenStorageTs_ctrl){ _lastSeenStorageTs_ctrl = ts; try{ handleIncoming(d.msg); }catch(e){} }
    }catch(e){}
}
setInterval(pollLocalStorageController, 500);
// New: poll the queue key and process messages pushed by other tabs
function pollQueueController(){ try{
        const rawq = localStorage.getItem('game-100mx-queue');
        if(!rawq) return;
        const q = JSON.parse(rawq) || [];
        if(!Array.isArray(q) || q.length===0) return;
        // process all entries
        q.forEach(item=>{ try{ handleIncoming(item.msg); }catch(e){} });
        // clear queue after processing
        try{ localStorage.removeItem('game-100mx-queue'); }catch(e){}
    }catch(e){}
}
setInterval(pollQueueController, 400);

// lightweight log: no-op for console; keep hidden DOM log only for debugging
function log(msg){ try{ const el=document.getElementById('log'); if(el){ const n = document.createElement('div'); n.textContent = msg; el.insertBefore(n, el.firstChild); } }catch(e){} }

function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

// Audio helpers: simple tones for invalid / confirm using WebAudio
const audioCtx = (typeof window !== 'undefined' && window.AudioContext) ? new AudioContext() : null;
function playTone(freq=440,duration=0.08, type='sine', gain=0.15){ try{ if(!audioCtx) return; const o = audioCtx.createOscillator(); const g = audioCtx.createGain(); o.type = type; o.frequency.value = freq; g.gain.value = gain; o.connect(g); g.connect(audioCtx.destination); o.start(); setTimeout(()=>{ o.stop(); }, duration*1000); }catch(e){ console.warn('audio error',e); } }
function playInvalidSound(){ // descending buzz
    playTone(880,0.05,'sine',0.1); setTimeout(()=>playTone(660,0.09,'square',0.09),60);
}
function playConfirmSound(){ playTone(880,0.06,'sine',0.08); setTimeout(()=>playTone(1200,0.06,'sine',0.06),80); }

function updateRemainingPointsDisplay(){ try{ if(!control) return; const target = Number(control.target || 0); const current = Number((control.scores.A||0) + (control.scores.B||0)); const remaining = Math.max(0, target - current); const pct = target>0? Math.min(100, Math.round((current/target)*100)):0; document.getElementById('targetDisplay').textContent = remaining; document.getElementById('progressFill').style.width = pct + '%'; }catch(e){} }

async function loadQuestions(){
    const total = Number(document.getElementById('totalInput').value) || 5;
    const seconds = Number(document.getElementById('secondsInput').value) || 60;
    const target = Number(document.getElementById('targetInput').value) || 30;
    document.getElementById('loadBtn').disabled = true;
    try{
        const res = await fetch(API_QUESTIONS_BASE);
        const data = await res.json();
        const active = Array.isArray(data) ? data.filter(q=>q&&q.is_active) : [];
        if(active.length < total){ alert('No hay suficientes preguntas activas'); document.getElementById('loadBtn').disabled=false; return; }
        const shuffled = active.sort(()=>0.5 - Math.random()).slice(0,total);
        const qpayloads = [];
        for(const q of shuffled){
            try{ const r = await fetch(`${API_QUESTIONS_BASE}/${q.id}/load`); const p = await r.json(); if(p && p.success){ qpayloads.push({id:q.id, text:p.question||q.name, answers:(p.answers||[]).map(a=>({...a, revealed:false, used:false}))}); } }catch(e){}
        }
        if(qpayloads.length < total){ alert('No se pudieron cargar suficientes preguntas'); document.getElementById('loadBtn').disabled=false; return; }
            // prepare control object but don't start
                control = { team: document.getElementById('teamInput').value.trim() || 'Equipo 1', questions: qpayloads, queue: qpayloads.map((_,i)=>i), player:0, position:0, scores:{A:0,B:0}, timer: seconds, timerId:null, secondsPerTurn: seconds, target: target };
                // playerAnswers: collect answers provided by each player (do not score immediately)
                control.playerAnswers = { 0: [], 1: [] };
                control.scoringMode = false; // when true, awarding will add points
            // initialize names from inputs if present
            const nA = document.getElementById('nameA').value.trim() || 'Jugador A';
            const nB = document.getElementById('nameB').value.trim() || 'Jugador B';
            control.names = { A: nA, B: nB };
            // notify display of team names
            send({ type: 'team_names', payload: { teams: [nA, nB] } });
            // set active player from selector
            const activeSel = document.getElementById('activeSelect'); if(activeSel) control.player = Number(activeSel.value || 0);
            document.getElementById('qTotal').textContent = total;
        document.getElementById('remainingCount').textContent = control.queue.length;
        document.getElementById('controlArea').style.display = 'block';
        renderCurrentQuestion();
        log('Preguntas cargadas. Listo para iniciar.');
    }catch(e){ console.error(e); alert('Error cargando preguntas'); }
    document.getElementById('loadBtn').disabled=false;
}

function renderCurrentQuestion(){
    if(!control) return;
    const qIdx = control.queue[control.position];
    const q = control.questions[qIdx];
    const nameA = control && control.names ? control.names.A : (document.getElementById('nameA') ? document.getElementById('nameA').value : 'Jugador A');
    const nameB = control && control.names ? control.names.B : (document.getElementById('nameB') ? document.getElementById('nameB').value : 'Jugador B');
    document.getElementById('playerLabel').textContent = `${control.player===0 ? nameA : nameB}`;
    // show position among original list
    const pos = control.position;
    document.getElementById('qIndex').textContent = (pos >= 0 ? pos + 1 : 1);
    document.getElementById('questionText').textContent = q.text;
    // answers list for host only: render as buttons to mark which answer was given
    const answersHtml = (q.answers||[]).map((a,i)=> {
        // during scoring we still want to be able to click answers to award points
        const disabledAttr = (a.used && !control.scoringMode) ? 'disabled' : '';
        // also disable while awaiting display ready
        const awaiting = control && control.awaitingReady ? 'disabled' : '';
        const opacity = (a.used && !control.scoringMode) || (control && control.awaitingReady) ? 'opacity:0.45;pointer-events:none' : '';
    return `
        <div style="margin-bottom:6px;display:flex;align-items:center;gap:8px;${opacity}">
            <div style="flex:1">${escapeHtml(a.text)} ${a.used?'<span style="color:#fbb;font-weight:700;margin-left:8px">(usada)</span>':''} · <small style='opacity:.8'>${a.count}</small></div>
            <div style="display:flex;gap:6px">
                <button class="btn-award" data-index="${i}" ${disabledAttr} ${awaiting}>Acierto</button>
                <button class="btn-mark-repeat" data-index="${i}" style="background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff" ${disabledAttr} ${awaiting}>Repetida</button>
            </div>
        </div>
    `}).join('');
    document.getElementById('answersList').innerHTML = answersHtml || '(sin respuestas disponibles)';
    document.getElementById('scoreA').textContent = control.scores.A;
    document.getElementById('scoreB').textContent = control.scores.B;
    document.getElementById('remainingCount').textContent = (control.queue.length - control.position);
    // update remaining points and progress
    updateRemainingPointsDisplay();
}

function sendShowQuestion(){
    if(!control || !control.queue || control.queue.length === 0) return;
    const qIdx = control.queue[control.position];
    const q = control.questions[qIdx];
    const pos = control.position + 1;
    // mark that we are waiting for the display to confirm readiness for this question
    control.awaitingReady = true;
    control.awaitingQIdx = qIdx;
    setControlsEnabled(false);
    log('Enviando pregunta al display y esperando confirmación (Listo)...');
    send({ type:'quick_game_question', payload:{ team: control.team, player: control.player, qIdx: qIdx, position: pos, question: q.text, total: control.questions.length, seconds: control.secondsPerTurn, remainingCount: control.queue.length }});
    // safety auto-start after timeout in case display ack is not received
    // NOTE: removed auto-start timeout — controller will wait strictly for quick_game_ready from the display
    if(control._readyTimeout){ clearTimeout(control._readyTimeout); control._readyTimeout = null; }
}

// Award points during scoring phase (adds to totals)
function awardPoints(answerIndex){
    if(!control || control.queue.length===0) return;
    const qIdx = control.queue[control.position];
    const q = control.questions[qIdx];
    const ans = q.answers && q.answers[answerIndex];
    if(!ans) return;
    if(ans.scored){ log('Respuesta ya puntuada'); playInvalidSound(); return; }
    const pts = Number(ans.count || 0);
    ans.scored = true; ans.revealed = true;
    // determine recipient from selector
    const recipient = (document.getElementById('pointsToSelect') || {}).value || 'A';
    const recipientPlayer = recipient === 'A' ? 0 : 1;
    if(recipientPlayer === 0) control.scores.A += pts; else control.scores.B += pts;
    send({ type:'quick_game_event', payload:{ action:'correct', team: control.team, player: recipientPlayer, qIdx: qIdx, answerIndex: answerIndex, answerText: ans.text, points: pts } });
    status(`Puntuado: +${pts} puntos (${recipient === 'A' ? 'Jugador A' : 'Jugador B'})`);
    try{ playConfirmSound(); }catch(e){}
    renderCurrentQuestion();
    updateRemainingPointsDisplay();
    checkTarget();
}

// Record the answer given by the active player (no points awarded now)
function recordGivenAnswer(answerIndex){
    if(!control || control.queue.length===0) return;
    const qIdx = control.queue[control.position];
    const q = control.questions[qIdx];
    const ans = q.answers && q.answers[answerIndex];
    const text = ans ? ans.text : '(respuesta)';
    if(ans && ans.scored){ log('Respuesta ya puntuada'); playInvalidSound(); return; }
    // store in playerAnswers for later scoring
    if(!Array.isArray(control.playerAnswers[control.player])) control.playerAnswers[control.player] = [];
    control.playerAnswers[control.player][control.position] = { qIdx: qIdx, answerIndex: answerIndex, answerText: text, points: (ans?Number(ans.count||0):0) };
    // notify display of the given answer (but do not award points yet)
    send({ type:'quick_game_event', payload:{ action:'given', team: control.team, player: control.player, qIdx: qIdx, position: control.position+1, answerIndex: answerIndex, answerText: text } });
    status(`Respuesta registrada: ${text}`);
    // advance to next question for this player
    control.position += 1;
    if(control.position >= control.queue.length){
        // finished answering block for this player -> enter scoring mode
        enterScoringMode();
    } else {
        renderCurrentQuestion(); sendShowQuestion();
    }
}

function checkTarget(){ if(!control) return; updateRemainingPointsDisplay(); const sum = (control.scores.A||0) + (control.scores.B||0); if(control.target && sum >= control.target){ finishGame(); } }


function markRepeat(answerIndex){
    if(!control || control.queue.length===0) return;
    const qIdx = control.queue[control.position];
    const q = control.questions[qIdx];
    const ans = q.answers && q.answers[answerIndex];
    // do not mark answer as globally 'used' here — we track given answers per player in control.playerAnswers
    // record as given but flagged repeated
    if(!Array.isArray(control.playerAnswers[control.player])) control.playerAnswers[control.player] = [];
    control.playerAnswers[control.player][control.position] = { qIdx: qIdx, answerIndex: answerIndex, answerText: ans ? ans.text : '(repetida)', points: 0, repeated: true };
    send({ type:'quick_game_event', payload:{ action:'repeat', team: control.team, player: control.player, qIdx: qIdx, answerIndex: answerIndex, answerText: ans ? ans.text : '' } });
    log('Marcada como repetida (X)');
    try{ playInvalidSound(); }catch(e){}
    // advance to next question
    control.position += 1;
    if(control.position >= control.queue.length){ enterScoringMode(); } else { renderCurrentQuestion(); sendShowQuestion(); }
    updateRemainingPointsDisplay();
}

function actionReveal(){
    if(!control || control.queue.length===0) return;
    const qIdx = control.queue[control.position];
    const q = control.questions[qIdx];
    send({ type:'quick_game_event', payload:{ action:'reveal', team: control.team, player: control.player, qIdx: qIdx, answers: q.answers || [], position: control.position+1 } });
    log('Reveladas respuestas (host)');
    (q.answers||[]).forEach(a=>{ a.revealed = true; });
    // mark as given for this player with combined reveal
    if(!Array.isArray(control.playerAnswers[control.player])) control.playerAnswers[control.player] = [];
    control.playerAnswers[control.player][control.position] = { qIdx: qIdx, answerIndex: null, answerText: (q.answers||[]).map(a=>a.text||'').join(' · '), points:0, revealed:true };
    control.position += 1;
    if(control.position >= control.queue.length){ enterScoringMode(); } else { renderCurrentQuestion(); sendShowQuestion(); }
}

function passQuestion(){
    if(!control || control.queue.length===0) return;
    // move current question to end of the queue
    const cur = control.queue.splice(control.position,1)[0];
    control.queue.push(cur);
    // ensure position remains valid
    if(control.position >= control.queue.length) control.position = 0;
    send({ type:'quick_game_event', payload:{ action:'pass', team: control.team, player: control.player, qIdx: cur } });
    log('Pregunta pasada — se volverá más tarde');
    renderCurrentQuestion();
    sendShowQuestion();
}

function nextQuestion(){
    if(!control) return;
    if(control.queue.length===0){ finishGame(); return; }
    control.position = (control.position + 1) % control.queue.length;
    renderCurrentQuestion();
    sendShowQuestion();
}

function startTurnTimer(){ if(!control) return; clearInterval(control.timerId); control.timer = control.secondsPerTurn || 60; document.getElementById('turnTimer').textContent = control.timer; control.timerId = setInterval(()=>{ control.timer -= 1; document.getElementById('turnTimer').textContent = control.timer; if(control.timer <= 0){ clearInterval(control.timerId); // time up for the whole block -> enter scoring for this player
        status('Tiempo acabado — entrando a fase de puntuación');
        enterScoringMode();
    } }, 1000); }

function enterScoringMode(){
    if(!control) return;
    clearInterval(control.timerId);
    control.scoringMode = true;
    // move to position 0 to allow scoring from first question
    control.position = 0;
    // show finish scoring button
    const fs = document.getElementById('btnFinishScoring'); if(fs) fs.style.display = ''; 
    const np = document.getElementById('btnNoPoint'); if(np) np.style.display = '';
    document.getElementById('btnStartScoring').style.display = 'none';
    status('Fase de puntuación — asigna puntos manualmente');
    setControlsEnabled(true);
    renderCurrentQuestion();
    // notify display
    send({ type:'quick_game_event', payload:{ action:'scoring_start', team: control.team, player: control.player } });
}

function finishScoring(){
    if(!control) return;
    control.scoringMode = false;
    const fs = document.getElementById('btnFinishScoring'); if(fs) fs.style.display = 'none';
    const np = document.getElementById('btnNoPoint'); if(np) np.style.display = 'none';
    document.getElementById('btnStartScoring').style.display = '';
    status('Puntuación finalizada para jugador');
    // notify display
    send({ type:'quick_game_event', payload:{ action:'scoring_end', team: control.team, player: control.player, scores: control.scores } });
    // switch to next player or finish
    control.position = 0;
    if(control.player === 0){
        control.player = 1;
        // send first question for next player
        status('Ahora Jugador B — enviando primera pregunta (esperando confirmación del display)');
        sendShowQuestion();
    } else {
        finishGame();
    }
}

function setControlsEnabled(enabled){ const ids = ['btnPass','btnRepeat','btnReveal','btnNext']; ids.forEach(id=>{ const el = document.getElementById(id); if(!el) return; el.disabled = !enabled; el.style.opacity = enabled ? 1 : 0.5; }); }

function status(txt){ try{ const el = document.getElementById('statusLine'); if(el) el.textContent = txt; else console.debug(txt); }catch(e){} }

function switchTurn(){
    if(!control) return;
    if(control.player === 0){
        control.player = 1;
        status('Tiempo acabado — ahora Jugador B (esperando display)');
        // do NOT start timer here — wait for display ack after sending question
        sendShowQuestion();
        renderCurrentQuestion();
    } else {
        finishGame();
    }
}

function finishGame(){ clearInterval(control.timerId); send({ type:'quick_game_end', payload:{ team: control.team, scores:{ A: control.scores.A, B: control.scores.B } } }); log(`Juego finalizado — A: ${control.scores.A} · B: ${control.scores.B}`); alert('Juego rápido finalizado. Revisa la pantalla pública para resultados.'); }

// wire buttons
document.getElementById('loadBtn').addEventListener('click', loadQuestions);
document.getElementById('startBtn').addEventListener('click', ()=>{ if(!control){ alert('Carga preguntas primero'); return; } // send first question but wait for display ack before starting timer/actions
    status('Juego iniciado — enviando primera pregunta (esperando confirmación del display)');
    sendShowQuestion();
});
// ping display for quick connectivity test
document.getElementById('pingBtn').addEventListener('click', ()=>{ send({ type:'ping', payload:{ ts: Date.now() } }); status('Ping enviado al display'); });
// delegate answer actions from answersList
document.getElementById('answersList').addEventListener('click', (ev)=>{
    const btn = ev.target.closest('button'); if(!btn) return;
    const idx = Number(btn.getAttribute('data-index'));
    // if we're in scoringMode, awardPoints should be used to add points; otherwise record the given answer
    if(control && control.scoringMode){
        if(btn.classList.contains('btn-award')){ awardPoints(idx); }
        else if(btn.classList.contains('btn-mark-repeat')){ markRepeat(idx); }
    } else {
        if(btn.classList.contains('btn-award')){ recordGivenAnswer(idx); }
        else if(btn.classList.contains('btn-mark-repeat')){ markRepeat(idx); }
    }
});
// update names UI and notify display when name inputs change
const nameAEl = document.getElementById('nameA'); const nameBEl = document.getElementById('nameB');
function sendNames(){ try{ const a = (nameAEl && nameAEl.value.trim()) || 'Jugador A'; const b = (nameBEl && nameBEl.value.trim()) || 'Jugador B'; if(control) control.names = { A: a, B: b }; send({ type:'team_names', payload:{ teams: [a,b] } }); status(`Nombres actualizados: ${a} • ${b}`); }catch(e){} }
if(nameAEl) nameAEl.addEventListener('change', sendNames);
if(nameBEl) nameBEl.addEventListener('change', sendNames);
// active player selector
const activeSel = document.getElementById('activeSelect'); if(activeSel){ activeSel.addEventListener('change', ()=>{ if(control) control.player = Number(activeSel.value); renderCurrentQuestion(); status(`Jugador activo cambiado a ${control.player===0? (control.names?control.names.A:'Jugador A') : (control.names?control.names.B:'Jugador B')}`); }); }
document.getElementById('btnRepeat').addEventListener('click', ()=>{ if(control) send({ type:'quick_game_event', payload:{ action:'repeat_manual', team: control.team, player: control.player } }); log('X manual enviada'); });
document.getElementById('btnReveal').addEventListener('click', actionReveal);
document.getElementById('btnStartScoring').addEventListener('click', ()=>{ enterScoringMode(); });
document.getElementById('btnFinishScoring').addEventListener('click', ()=>{ if(confirm('Finalizar puntuación para este jugador?')) finishScoring(); });
document.getElementById('btnNoPoint').addEventListener('click', ()=>{
    // mark current answer as no point for this position
    if(!control) return; 
    // during scoring mode, record that this slot gets no points
    const qIdx = control.queue[control.position];
    const entry = { qIdx: qIdx, answerIndex: null, answerText: '(sin punto)', points: 0, noPoint:true };
    if(!Array.isArray(control.playerAnswers[control.player])) control.playerAnswers[control.player]=[];
    control.playerAnswers[control.player][control.position] = entry;
    // notify display
    send({ type:'quick_game_event', payload:{ action:'no_point', team: control.team, player: control.player, qIdx: qIdx, position: control.position+1, info: entry } });
    status('Marcado: NO otorgar punto');
    // advance to next
    control.position += 1; if(control.position >= control.queue.length){ finishScoring(); } else { renderCurrentQuestion(); }
});
// add pass button if not present
let passBtn = document.getElementById('btnPass'); if(!passBtn){ const c = document.querySelector('.controls'); if(c){ const b = document.createElement('button'); b.id='btnPass'; b.textContent='Pasar'; b.style.background='linear-gradient(90deg,#f59e0b,#f97316)'; c.insertBefore(b, c.firstChild); passBtn = b; } }
if(passBtn) passBtn.addEventListener('click', passQuestion);
document.getElementById('btnNext').addEventListener('click', nextQuestion);
document.getElementById('btnEnd').addEventListener('click', ()=>{ if(confirm('Finalizar juego rápido?')) finishGame(); });

// listen for ack or controller responses (optional)
// handle incoming messages (BroadcastChannel or storage fallback)
function handleIncoming(msg){ try{ if(!msg || !msg.type) return; if(msg.type === 'quick_game_ready'){ const p = msg.payload || {}; // ensure this ack matches the question we sent
                    if(control && control.awaitingReady && (typeof p.qIdx !== 'undefined') && p.qIdx === control.awaitingQIdx){
                        control.awaitingReady = false; control.awaitingQIdx = null; if(control._readyTimeout){ clearTimeout(control._readyTimeout); control._readyTimeout = null; } status('Display confirmó Listo — comenzando turno'); setControlsEnabled(true); startTurnTimer();
            } else {
                // ack doesn't match current question — ignore
                console.debug('quick_game_ready ignored', p);
            }
            return;
        }
    }catch(e){ console.error(e); }
}

if(usingBC && bc){ bc.onmessage = (ev)=>{ try{ handleIncoming(ev.data); }catch(e){} }; } else {
    // storage fallback
    window.addEventListener('storage', (ev)=>{ if(!ev.key || ev.key !== 'game-100mx') return; try{ const d = JSON.parse(ev.newValue); handleIncoming(d.msg); }catch(e){} });
}

</script>
</body>
</html>