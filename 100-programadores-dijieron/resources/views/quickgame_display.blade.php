<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display - Juego rápido</title>
    @vite('resources/css/app.css')
    <style>
        body{background:linear-gradient(180deg,#031018,#001217);color:#cfe;font-family:Inter,system-ui;margin:0}
        .wrap{max-width:1200px;margin:20px auto;padding:18px}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
        .title{font-size:28px;font-weight:800;letter-spacing:1px}
        .teams{display:flex;gap:16px}
        .team{background:rgba(255,255,255,0.02);padding:12px;border-radius:10px;min-width:180px;text-align:center}
        .team .name{font-weight:700;font-size:18px}
        .team .score{font-size:28px;color:#7ef3d6;margin-top:6px}
        .questionBox{background:rgba(0,0,0,0.35);padding:22px;border-radius:12px;border:1px solid rgba(102,252,241,0.04);margin-bottom:12px}
        .questionText{font-size:26px;font-weight:800;text-align:center}
    .roundBadge{font-size:18px;font-weight:800;color:#7ef3d6;margin-top:6px;text-align:center}
        .timer{font-size:36px;font-weight:900;color:#7ef3d6;margin-top:8px;text-align:center}
        .answersGrid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:14px}
        .answer{background:rgba(255,255,255,0.02);padding:12px;border-radius:8px;min-height:54px;display:flex;flex-direction:column;justify-content:center}
        .answer.blank{color:rgba(255,255,255,0.12);font-style:italic}
        .answer.revealed{color:#cff;font-weight:700}
        .answer .pts{font-size:14px;opacity:0.8;margin-top:6px}
        .status{opacity:0.9;text-align:center;margin-top:8px}
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div class="title">1100100 Devs Dijieron — Ronda rápida</div>
        <div class="teams">
            <div class="team" id="teamA"><div class="name" id="teamAName">Jugador A</div><div class="score" id="teamAScore">0</div></div>
            <div class="team" id="teamB"><div class="name" id="teamBName">Jugador B</div><div class="score" id="teamBScore">0</div></div>
        </div>
    </div>

    <div class="questionBox">
        <div class="questionText" id="questionText">(esperando pregunta)</div>
        <div class="roundBadge" id="roundBadge">Ronda: -</div>
        <div class="timer" id="displayTimer">--</div>
        <div class="status" id="displayStatus">Listo</div>
        <!-- Fixed 5-slot board: each row shows question and the response that was given -->
        <div id="boardSlots" style="margin-top:18px;display:flex;flex-direction:column;gap:12px">
            <!-- rows injected by JS -->
        </div>
    </div>
</div>

<script>
// BroadcastChannel + storage bridge (mirror del controlador)
let bc = null; let usingBC = false;
try{ if(typeof BroadcastChannel !== 'undefined'){ bc = new BroadcastChannel('game-100mx'); usingBC = true; } }catch(e){}

function pushToQueue(item){ try{ const raw = localStorage.getItem('game-100mx-queue'); const arr = raw ? JSON.parse(raw) : []; arr.push(item); localStorage.setItem('game-100mx-queue', JSON.stringify(arr)); }catch(e){ try{ localStorage.setItem('game-100mx-queue', JSON.stringify([item])); }catch(e){} } }
function send(msg){ try{ if(usingBC && bc){ try{ bc.postMessage(msg); }catch(e){} } }catch(e){} try{ const envelope = { msg: msg, ts: Date.now(), id: Math.floor(Math.random()*1000000) }; pushToQueue(envelope); localStorage.setItem('game-100mx', JSON.stringify({last:envelope, ts:Date.now(), msg: msg})); }catch(e){} }

// Audio helpers
const audioCtx = (typeof window !== 'undefined' && window.AudioContext) ? new AudioContext() : null;
function playTone(freq=440,duration=0.08, type='sine', gain=0.15){ try{ if(!audioCtx) return; const o = audioCtx.createOscillator(); const g = audioCtx.createGain(); o.type = type; o.frequency.value = freq; g.gain.value = gain; o.connect(g); g.connect(audioCtx.destination); o.start(); setTimeout(()=>{ o.stop(); }, duration*1000); }catch(e){} }
function playConfirm(){ playTone(880,0.06,'sine',0.08); setTimeout(()=>playTone(1200,0.05,'sine',0.06),80); }
function playInvalid(){ playTone(660,0.06,'square',0.08); }

// Display state
// roundSlots: array of length 5 with { question, givenAnswer, points, qIdx }
let state = { question: null, answers: [], seconds:60, timerId:null, qIdx:null, total:0, currentPosition:null, roundSlots: [null,null,null,null,null] };

function renderAnswers(){
    // kept for backward compatibility but we no longer display answers grid
}

function renderBoardSlots(){
    const wrap = document.getElementById('boardSlots');
    wrap.innerHTML = '';
    for(let i=0;i<5;i++){
        const slot = state.roundSlots[i] || { question: '........', givenAnswer: '', points: null, qIdx: null };
        const row = document.createElement('div');
        row.style.display = 'flex'; row.style.gap = '12px'; row.style.alignItems = 'center'; row.style.transition='box-shadow .18s,transform .12s';
        // highlight current row
        if(state.currentPosition === (i+1)){
            row.style.boxShadow = '0 6px 20px rgba(16,184,166,0.08)'; row.style.transform='translateY(-2px)';
        }
        // left: question
        const left = document.createElement('div');
        left.style.flex = '1'; left.style.background = 'rgba(255,255,255,0.015)'; left.style.padding = '12px'; left.style.borderRadius = '8px'; left.style.minHeight = '48px';
        left.style.display = 'flex'; left.style.alignItems = 'center'; left.style.color='rgba(255,255,255,0.45)';
        left.innerHTML = `<div style="font-weight:700">${escapeHtml(slot.question || '........')}</div>`;
        // right: given answer
        const right = document.createElement('div');
        right.style.width = '320px'; right.style.background = 'rgba(0,0,0,0.25)'; right.style.padding = '12px'; right.style.borderRadius = '8px'; right.style.minHeight = '48px';
        right.style.display = 'flex'; right.style.flexDirection = 'column'; right.style.justifyContent = 'center';
    const given = slot.givenAnswer ? escapeHtml(slot.givenAnswer) : '........';
    right.innerHTML = `<div style="font-weight:700;color:${slot.noPoint? '#f87171' : '#7ef3d6'}">${given}</div>` + (slot.points? `<div style="opacity:.9;font-size:12px;margin-top:6px">+${slot.points} pts</div>` : '');
        row.appendChild(left); row.appendChild(right);
        wrap.appendChild(row);
    }
}

function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

function startDisplayTimer(seconds){ clearInterval(state.timerId); state.seconds = Number(seconds)||60; const el = document.getElementById('displayTimer'); el.textContent = state.seconds; state.timerId = setInterval(()=>{ state.seconds -= 1; if(state.seconds < 0){ clearInterval(state.timerId); document.getElementById('displayStatus').textContent = 'Tiempo acabado'; return; } document.getElementById('displayTimer').textContent = state.seconds; }, 1000); }

function handleIncoming(msg){ try{ if(!msg || !msg.type) return; const t = msg.type; const p = msg.payload || {}; if(t === 'quick_game_question'){ // show question and answers skeleton, then ack ready
            state.question = p.question || '(sin texto)'; state.qIdx = p.qIdx; state.total = p.total || 0; state.answers = (p.answers||[]).map(a=>({ text:a.text || a.name || '', count:a.count||0, revealed:false })); document.getElementById('questionText').textContent = state.question; document.getElementById('displayStatus').textContent = `Pregunta ${p.position || '?'} / ${state.total}`; renderAnswers(); // start local timer but wait a brief moment to ensure display has rendered
                // record question text in the corresponding slot (position provided by controller)
                const pos = Number(p.position || 0);
                if(pos >=1 && pos <=5){ state.currentPosition = pos; state.roundSlots[pos-1] = Object.assign({}, state.roundSlots[pos-1] || {}, { question: state.question, qIdx: state.qIdx }); }
                renderBoardSlots(); // start local timer
                startDisplayTimer(p.seconds || 60);
                // send ready ack
                send({ type: 'quick_game_ready', payload: { qIdx: state.qIdx, position: state.currentPosition } });
            return;
        }
        if(t === 'team_names'){ const teams = p.teams || []; if(teams[0]) document.getElementById('teamAName').textContent = teams[0]; if(teams[1]) document.getElementById('teamBName').textContent = teams[1]; return; }
        if(t === 'quick_game_event'){ const ev = p;
            if(ev.action === 'given'){ // show the given answer but do not award points
                let slotIndex = -1;
                if(typeof ev.qIdx !== 'undefined') slotIndex = (state.roundSlots||[]).findIndex(s=>s && s.qIdx === ev.qIdx);
                if(slotIndex === -1 && state.currentPosition) slotIndex = state.currentPosition - 1;
                if(slotIndex === -1) slotIndex = (typeof ev.position !== 'undefined' ? (Number(ev.position)-1) : 0);
                state.roundSlots[slotIndex] = Object.assign({}, state.roundSlots[slotIndex] || {}, { givenAnswer: ev.answerText || '(respuesta)', points: 0, qIdx: ev.qIdx });
                // set current position for highlighting
                if(typeof ev.position !== 'undefined') state.currentPosition = Number(ev.position);
                renderBoardSlots(); return; }
            if(ev.action === 'correct'){ // reveal specific answer and update scores
                // try to find slot by qIdx or currentPosition
                let slotIndex = -1;
                if(typeof ev.qIdx !== 'undefined'){
                    slotIndex = (state.roundSlots||[]).findIndex(s=>s && s.qIdx === ev.qIdx);
                }
                if(slotIndex === -1 && state.currentPosition){ slotIndex = state.currentPosition - 1; }
                if(slotIndex === -1) slotIndex = 0;
                state.roundSlots[slotIndex] = Object.assign({}, state.roundSlots[slotIndex] || {}, { givenAnswer: ev.answerText || '(respuesta)', points: ev.points || 0, qIdx: ev.qIdx });
                // update scores
                if(typeof ev.player !== 'undefined'){ if(ev.player===0) document.getElementById('teamAScore').textContent = Number(document.getElementById('teamAScore').textContent||0) + Number(ev.points||0); else document.getElementById('teamBScore').textContent = Number(document.getElementById('teamBScore').textContent||0) + Number(ev.points||0); }
                renderBoardSlots(); playConfirm(); return; }
            if(ev.action === 'repeat' || ev.action === 'repeat_manual'){ // mark as repeated X (if answerIndex provided)
                let slotIndex = -1;
                if(typeof ev.qIdx !== 'undefined') slotIndex = (state.roundSlots||[]).findIndex(s=>s && s.qIdx === ev.qIdx);
                if(slotIndex === -1 && state.currentPosition) slotIndex = state.currentPosition - 1;
                if(slotIndex === -1) slotIndex = 0;
                state.roundSlots[slotIndex] = Object.assign({}, state.roundSlots[slotIndex] || {}, { givenAnswer: '(Repetida)', points: 0, qIdx: ev.qIdx });
                renderBoardSlots(); playInvalid(); return; }
            if(ev.action === 'reveal'){ // reveal all
                // Mark current slot as revealed with list of answers concatenated
                const posIdx = state.currentPosition ? state.currentPosition-1 : 0;
                if(!state.roundSlots[posIdx]) state.roundSlots[posIdx] = {};
                state.roundSlots[posIdx].givenAnswer = (p.answers||[]).map(a=>a.text||a.name||'').join(' · ');
                renderBoardSlots(); return; }
            if(ev.action === 'no_point'){ // mark slot as incorrect / no point
                const pos = typeof ev.position !== 'undefined' ? Number(ev.position)-1 : ((state.currentPosition?state.currentPosition-1:0));
                state.roundSlots[pos] = Object.assign({}, state.roundSlots[pos]||{}, { givenAnswer: '(sin punto)', points: 0, noPoint: true });
                state.currentPosition = pos+1;
                renderBoardSlots(); playInvalid(); return; }
            if(ev.action === 'scoring_start'){ document.getElementById('displayStatus').textContent = 'Fase de puntuación'; return; }
            if(ev.action === 'scoring_end'){ document.getElementById('displayStatus').textContent = 'Puntuación finalizada'; return; }
            if(ev.action === 'pass'){ document.getElementById('displayStatus').textContent = 'Pregunta pasada'; return; }
        }
        if(t === 'quick_game_end'){ // show final scores
            const s = p.scores || {}; document.getElementById('teamAScore').textContent = s.A || document.getElementById('teamAScore').textContent; document.getElementById('teamBScore').textContent = s.B || document.getElementById('teamBScore').textContent; document.getElementById('displayStatus').textContent = 'Juego finalizado'; clearInterval(state.timerId); return; }
    }catch(e){ console.error(e); }
}

if(usingBC && bc){ bc.onmessage = (ev)=>{ try{ handleIncoming(ev.data); }catch(e){} }; } else {
    window.addEventListener('storage', (ev)=>{ if(!ev.key || ev.key !== 'game-100mx') return; try{ const d = JSON.parse(ev.newValue || '{}'); if(d && d.msg) handleIncoming(d.msg); }catch(e){} });
}

// poll queue fallback (in case postMessage didn't propagate)
setInterval(()=>{ try{ const rawq = localStorage.getItem('game-100mx-queue'); if(!rawq) return; const q = JSON.parse(rawq) || []; if(!Array.isArray(q) || q.length===0) return; q.forEach(item=>{ try{ handleIncoming(item.msg); }catch(e){} }); localStorage.removeItem('game-100mx-queue'); }catch(e){} }, 500);

// helper to initialize blank display
renderBoardSlots();
</script>
</body>
</html>
