<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tablero — Tech Edition</title>
    <style>
        /* Tech / neon / glass aesthetic */
        :root{--bg:#071024;--glass:rgba(255,255,255,0.04);--accent:#00e5ff;--accent2:#7b61ff;--muted:#8aa0b1}
    html,body{height:100%;margin:0;background:linear-gradient(180deg,#030617 0%,#071024 60%);font-family:Inter,ui-sans-serif,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial;color:#e6f7ff}
    .container{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:18px}
    /* center the board card reliably */
    .dashboard{display:flex;align-items:center;justify-content:center;width:100%;}
    .dashboard .board-wrapper{width:1000px;max-width:96%;display:flex;gap:22px;align-items:center;justify-content:center}
    .dashboard .board{flex:1 1 auto}

        /* Left: board */
        .board{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:18px;backdrop-filter: blur(6px);box-shadow:0 8px 40px rgba(2,6,23,0.6), inset 0 1px 0 rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.04)}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
        .title{font-weight:800;font-size:20px;letter-spacing:0.6px;color:var(--accent)}
        .status{font-size:13px;color:var(--muted)}

        .scoreline{display:flex;gap:10px;align-items:center;margin-bottom:8px}
        .score{flex:0 0 140px;padding:12px;border-radius:10px;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.08));border:1px solid rgba(0,255,255,0.04);box-shadow:0 6px 18px rgba(0,0,0,0.5);}
        .score .num{font-size:34px;font-weight:900;color:var(--accent);letter-spacing:2px}
        .score .lbl{font-size:11px;color:var(--muted);margin-top:6px}

        /* Grid of answers */
        .answers-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-top:10px}
        .cell{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:10px;padding:14px;border:1px solid rgba(255,255,255,0.04);min-height:64px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
        .cell .text{font-size:16px;color:#dff8ff;min-height:34px}
        .cell .count{font-family:monospace;font-weight:800;color:var(--accent);font-size:20px}
        .cell.locked{opacity:0.14;filter:grayscale(0.25);}

        /* Neon glow for revealed cells */
        .cell.revealed{box-shadow:0 6px 30px rgba(0,229,255,0.06), 0 0 18px rgba(123,97,255,0.06) inset;border:1px solid rgba(0,229,255,0.12)}

        /* Right column: meta & logs */
        .panel{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:12px;padding:14px;border:1px solid rgba(255,255,255,0.03)}
        .panel h3{margin:0 0 8px 0;color:var(--accent2)}
        .chip{font-size:12px;color:var(--muted);margin-bottom:10px}
        .logs{background:rgba(0,0,0,0.16);border-radius:8px;padding:8px;height:200px;overflow:auto;color:#cfeffb;font-size:12px}

        /* subtle animated grid background */
        .bg-grid{position:absolute;inset:0;pointer-events:none;mask-image:linear-gradient(180deg, rgba(0,0,0,1), rgba(0,0,0,0.2));}
        .small{font-size:12px;color:var(--muted)}

        @keyframes strikeAppear {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.3) rotate(10deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

    @media (max-width:880px){ .dashboard .board-wrapper{flex-direction:column;align-items:stretch} .panel{order:2} }
    </style>
</head>
<body>
<div class="container">
    <div class="dashboard">
        <div class="board-wrapper">
        <div class="board">
            <div class="header" style="align-items:flex-start;gap:12px;flex-direction:column;display:flex">
                <div style="display:flex;align-items:center;gap:18px;width:100%">
                    <!-- left score -->
                    <div style="flex:0 0 120px;display:flex;flex-direction:column;align-items:center">
                        <div class="score" style="width:120px;padding:10px">
                            <div class="num" id="mainScore">000</div>
                            <div class="lbl">Puntos</div>
                        </div>
                    </div>

                    <div style="flex:1">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div class="title">Tablero — Tech Edition</div>
                            <div class="status">Estado: <strong id="state">Esperando</strong></div>
                        </div>

                        <!-- Question placed prominently above the board -->
                        <div style="margin-top:12px;margin-bottom:6px;padding:12px;border-radius:10px;background:linear-gradient(90deg, rgba(0,229,255,0.02), rgba(123,97,255,0.02));border:1px solid rgba(0,229,255,0.06);font-weight:700;color:#dffaff"> 
                            <span style="color:var(--muted);font-weight:600;margin-right:8px">Pregunta:</span>
                            <span id="question">(esperando)</span>
                        </div>
                        <!-- Round points display -->
                        <div style="margin-top:6px;margin-bottom:6px;padding:8px;border-radius:8px;background:rgba(0,0,0,0.04);color:#cfeffb;font-weight:700">Puntos de la ronda: <span id="roundPointsDisplay">0</span></div>

                        <!-- Strikes (X's) Display - Horizontal -->
                        <div id="strikesDisplay" style="margin-top:10px;margin-bottom:10px;display:flex;gap:15px;justify-content:center;align-items:center;flex-direction:row;">
                            <!-- X's rendered here by JS -->
                        </div>

                        <!-- Team score tables (Familia A / Familia B ejemplo) -->
                        <div id="teamScores" style="margin-top:10px;display:flex;gap:8px;align-items:stretch">
                            <!-- individual team blocks are rendered by JS -->
                        </div>
                    </div>

                    <!-- right score -->
                    <div style="flex:0 0 120px;display:flex;flex-direction:column;align-items:center">
                        <div class="score" style="width:120px;padding:10px">
                            <div class="num" id="roundScore">000</div>
                            <div class="lbl">Ronda</div>
                            <div class="lbl small" id="roundWinner" style="margin-top:6px;color:var(--muted)"></div>
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
        <!-- <aside class="panel">
            <h3>Panel</h3>
            <div class="chip">Tema: Desarrollo / Ingeniería de Software</div>
            <div class="chip">Conexión: <span id="connType" class="small">-</span></div>
        
        </div>
        </aside> -->
    </div>
</div>

<div class="bg-grid" aria-hidden>
    <!-- decorative, no semantics -->
</div>

<!-- Countdown overlay -->
<div id="countdownOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;">
    <div style="text-align:center;">
        <div id="countdownNumber" style="font-size:160px;font-weight:900;color:#00e5ff;text-shadow:0 0 60px rgba(0,229,255,0.8), 0 0 120px rgba(0,229,255,0.5);animation:pulse 1s infinite;">5</div>
        <div style="font-size:24px;color:#8aa0b1;margin-top:20px;letter-spacing:4px;text-transform:uppercase;">Iniciando ronda...</div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}
</style>

<script>
// Keep the same messaging contract: BroadcastChannel primary, localStorage fallback
let channel = null; let usingBroadcast = false;
try{ if (typeof BroadcastChannel !== 'undefined'){ channel = new BroadcastChannel('game-100mx'); usingBroadcast = true; document.getElementById('connType').textContent = 'BroadcastChannel'; console.debug('[board] bc open'); } }catch(e){ console.debug('[board] bc err', e); }

function sendMessage(msg){ if(usingBroadcast && channel) return channel.postMessage(msg); try{ localStorage.setItem('game-100mx', JSON.stringify({msg, ts:Date.now()})); return true; }catch(e){ console.debug('[board] ls fail', e); return false; } }

function addStorageListener(fn){ window.addEventListener('storage', (ev)=>{ if(!ev.key || ev.key !== 'game-100mx') return; try{ const d = JSON.parse(ev.newValue); fn(d.msg); }catch(e){} }); }

if(usingBroadcast && channel){ channel.onmessage = (ev)=>{ handleIncoming(ev.data); }; } else { addStorageListener((m)=>{ handleIncoming(m); }); document.getElementById('connType').textContent = 'localStorage (fallback)'; }

const answersEl = document.getElementById('answers'); const stateEl = document.getElementById('state'); const questionEl = document.getElementById('question'); const mainScore = document.getElementById('mainScore'); const roundScore = document.getElementById('roundScore');
let answers = [];
let currentRound = {points:0, teams:[], accumulatedPoints:0};
let roundReadySent = false;
let teamScores = {}; // { 'Familia A': 0, 'Familia B': 0 }
let strikeCount = 0; // Counter for X's

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
        cell.innerHTML = `<div class="text">${a.revealed ? escapeHtml(a.text) : '•••••••••••••••'}</div><div class="count">${a.revealed ? a.count : '---'}</div>`;
        answersEl.appendChild(cell);
        if(a.revealed) total += Number(a.count||0);
    }

    mainScore.textContent = String(total).padStart(3,'0');
    roundScore.textContent = '000';
    renderTeamScores();
}

function renderTeamScores(){
    const el = document.getElementById('teamScores');
    if(!el) return;
    el.innerHTML = '';
    // ensure at least two example families if none present
    // if there are no teams, don't render any team boxes (controller is authoritative)
    if(Object.keys(teamScores).length === 0){ return; }
    Object.keys(teamScores).forEach(name=>{
        const wrapper = document.createElement('div');
        wrapper.style.flex = '0 0 160px';
        wrapper.style.padding = '8px';
        wrapper.style.borderRadius = '8px';
        wrapper.style.background = 'linear-gradient(180deg, rgba(255,255,255,0.01), rgba(0,0,0,0.04))';
        wrapper.style.border = '1px solid rgba(255,255,255,0.03)';
        wrapper.innerHTML = `<div style="font-size:12px;color:var(--muted);">${escapeHtml(name)}</div><div style="font-weight:800;font-size:20px;color:var(--accent);">${String(teamScores[name]||0).padStart(3,'0')}</div>`;
        el.appendChild(wrapper);
    });
}

function appendLog(txt){}

function handleIncoming(msg){ if(!msg || !msg.type) return;
    if(msg.type === 'init'){
        answers = (msg.payload.answers||[]).map(a=>({...a}));
        stateEl.textContent = msg.payload.state || 'Listo';
        questionEl.textContent = msg.payload.question ? msg.payload.question : '(sin texto)';
        render();
        // a new init usually means a new round lifecycle; don't auto-emit round_ready here
    } else if(msg.type === 'reveal'){
        const idx = msg.payload.index;
        if(answers[idx]) answers[idx].revealed = true;
        render();
    } else if(msg.type === 'state'){
        stateEl.textContent = msg.payload.state;
    } else if(msg.type === 'countdown'){
        const countdownOverlay = document.getElementById('countdownOverlay');
        const countdownNumber = document.getElementById('countdownNumber');
        const count = msg.payload.count;
        
        if(count > 0){
            countdownOverlay.style.display = 'flex';
            countdownNumber.textContent = count;
        } else {
            countdownOverlay.style.display = 'none';
        }
    } else if(msg.type === 'update_strikes'){
        // Update strike count
        strikeCount = Number((msg.payload && msg.payload.count) || 0);
        renderStrikes();
    } else if(msg.type === 'round_points'){
        // controller started a round; store points & teams and update display
        currentRound.points = Number((msg.payload && msg.payload.points) || 0);
        currentRound.teams = (msg.payload && msg.payload.teams) || [];
        currentRound.accumulatedPoints = 0; // reset accumulated points for new round
        roundReadySent = false;
        document.getElementById('roundPointsDisplay').textContent = '0'; // start at 0
        // clear previous round winner
        const rw = document.getElementById('roundWinner'); if(rw) rw.textContent = '';
        // reset strikes
        strikeCount = 0;
        renderStrikes();
    // replace teamScores with teams sent by controller (controller is authoritative)
    const newScores = {};
    (currentRound.teams || []).forEach(t=>{ newScores[t] = Number(teamScores[t] || 0); });
    teamScores = newScores;
        persistTeamScores();
        renderTeamScores();
    } else if(msg.type === 'update_round_total'){
        // Update accumulated round points display
        const accumulatedPts = Number((msg.payload && msg.payload.points) || 0);
        currentRound.accumulatedPoints = accumulatedPts;
        document.getElementById('roundPointsDisplay').textContent = String(accumulatedPts);
    } else if(msg.type === 'assign_points'){
        const p = (msg.payload && Number(msg.payload.points)) || 0;
        const team = (msg.payload && msg.payload.team) || '';
        // show assigned points and the team that won the round
        roundScore.textContent = String(p).padStart(3,'0');
        const rw = document.getElementById('roundWinner'); if(rw) rw.textContent = team ? ('Ganador: ' + team) : '';
        // optionally, update the main score visually (keep mainScore as sum of revealed answers)
        if(team){
            if(!(team in teamScores)) teamScores[team] = 0;
            teamScores[team] = Number(teamScores[team] || 0) + Number(p || 0);
            persistTeamScores();
            renderTeamScores();
        }
    } else if(msg.type === 'reset_all'){
        // Complete reset of everything
        answers = [];
        teamScores = {};
        currentRound = {points:0, teams:[], accumulatedPoints:0};
        strikeCount = 0;
        roundReadySent = false;
        
        // Clear localStorage
        localStorage.removeItem('game-team-scores');
        
        // Update all displays
        questionEl.textContent = '(esperando)';
        stateEl.textContent = 'Listo';
        document.getElementById('roundPointsDisplay').textContent = '0';
        roundScore.textContent = '000';
        mainScore.textContent = '000';
        const rw = document.getElementById('roundWinner'); 
        if(rw) rw.textContent = '';
        
        // Reset strikes and team scores
        renderStrikes();
        renderTeamScores();
        render();
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
</script>

</body>
</html>
    </html>