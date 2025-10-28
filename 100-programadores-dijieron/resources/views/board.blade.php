<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1100100 Devs Dijeron — Tablero</title>
    @vite('resources/css/board.css')
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

<!-- Steal overlay (centered) -->
<div id="stealOverlay" class="steal-overlay">
    <div id="stealCard" class="steal-card" style="animation:steal-pop .35s ease-out, steal-glow 1.2s ease-in-out infinite alternate">
        <div class="steal-title">¡Robo de puntos!</div>
        <div id="stealOverlaySub" class="steal-sub"></div>
    </div>
</div>

<!-- Countdown overlay -->
<div id="countdownOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;">
    <div style="text-align:center;">
        <div id="countdownNumber" style="font-size:160px;font-weight:900;color:#00e5ff;text-shadow:0 0 60px rgba(0,229,255,0.8), 0 0 120px rgba(0,229,255,0.5);animation:pulse 1s infinite;">5</div>
        <div style="font-size:24px;color:#8aa0b1;margin-top:20px;letter-spacing:4px;text-transform:uppercase;">Iniciando ronda...</div>
    </div>
</div>

<script>
// Keep the same messaging contract: BroadcastChannel primary, localStorage fallback
let channel = null; let usingBroadcast = false;
try{ if (typeof BroadcastChannel !== 'undefined'){ channel = new BroadcastChannel('game-100mx'); usingBroadcast = true; document.getElementById('connType').textContent = 'BroadcastChannel'; console.debug('[board] bc open'); } }catch(e){ console.debug('[board] bc err', e); }

function sendMessage(msg){ if(usingBroadcast && channel) return channel.postMessage(msg); try{ localStorage.setItem('game-100mx', JSON.stringify({msg, ts:Date.now()})); return true; }catch(e){ console.debug('[board] ls fail', e); return false; } }

function addStorageListener(fn){ window.addEventListener('storage', (ev)=>{ if(!ev.key || ev.key !== 'game-100mx') return; try{ const d = JSON.parse(ev.newValue); fn(d.msg); }catch(e){} }); }

if(usingBroadcast && channel){ channel.onmessage = (ev)=>{ handleIncoming(ev.data); }; } else { addStorageListener((m)=>{ handleIncoming(m); }); document.getElementById('connType').textContent = 'localStorage (fallback)'; }

const answersEl = document.getElementById('answers'); const stateEl = document.getElementById('state'); const questionEl = document.getElementById('question'); 
const team1ScoreEl = document.getElementById('team1Score'); const team1NameEl = document.getElementById('team1Name');
const team2ScoreEl = document.getElementById('team2Score'); const team2NameEl = document.getElementById('team2Name');
const team1CardEl = document.getElementById('team1Card');
const team2CardEl = document.getElementById('team2Card');
let answers = [];
let currentRound = {points:0, teams:[], accumulatedPoints:0};
let roundReadySent = false;
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
        playSuccessSound(); // ✅ Success sound
        render(); // render triggers the reveal animation on inserted .cell.revealed
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
        const prevCount = strikeCount;
        strikeCount = Number((msg.payload && msg.payload.count) || 0);
        if(strikeCount > prevCount) playErrorSound(); // ❌ Compile error sound
        renderStrikes();
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
        const roundNumEl = document.getElementById('roundNumberDisplay');
        if(roundNumEl) roundNumEl.textContent = `Ronda: ${roundNum}`;
        // Update multiplier display
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

        // Toggle card highlight
        const t1Name = team1NameEl ? team1NameEl.textContent : '';
        const t2Name = team2NameEl ? team2NameEl.textContent : '';
        if(team1CardEl){ team1CardEl.classList.toggle('active', !!activeTeam && activeTeam === t1Name); }
        if(team2CardEl){ team2CardEl.classList.toggle('active', !!activeTeam && activeTeam === t2Name); }

        renderTeamScores();
    } else if(msg.type === 'assign_points'){
        const p = (msg.payload && Number(msg.payload.points)) || 0;
        const team = (msg.payload && msg.payload.team) || '';
        // Update team scores
        if(team){
            if(!(team in teamScores)) teamScores[team] = 0;
            teamScores[team] = Number(teamScores[team] || 0) + Number(p || 0);
            persistTeamScores();
            renderTeamScores();
        }
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
</script>

</body>
</html>
    </html>