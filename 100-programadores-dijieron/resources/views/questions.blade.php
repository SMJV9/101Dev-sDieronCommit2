<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banco de Preguntas - 1100100 Devs Dijeron</title>
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
        
        input[type="text"], input[type="number"]{
            background:rgba(0,0,0,0.5);
            border:1px solid rgba(102,252,241,0.3);
            color:var(--text);
            padding:10px 14px;
            border-radius:6px;
            font-size:14px;
            font-family:'Fira Code', monospace;
            transition:all 0.2s;
            width:100%;
        }
        
        input[type="text"]:focus, input[type="number"]:focus{
            outline:none;
            border-color:var(--accent);
            box-shadow:0 0 20px rgba(102,252,241,0.15), inset 0 0 20px rgba(102,252,241,0.05);
        }
        
        button{
            background:linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            color:#0b0c10;
            border:none;
            padding:10px 18px;
            border-radius:6px;
            font-weight:700;
            font-size:13px;
            cursor:pointer;
            transition:all 0.2s;
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
        
        .btn-danger{
            background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color:white;
        }
        
        .btn-secondary{
            background:rgba(122,128,132,0.3);
            color:var(--text);
            box-shadow:none;
        }
        
        .question-card{
            background:rgba(0,0,0,0.5);
            border:1px solid rgba(102,252,241,0.2);
            border-radius:8px;
            padding:16px;
            margin-bottom:12px;
            transition:all 0.2s;
        }
        
        .question-card:hover{
            border-color:rgba(102,252,241,0.4);
            box-shadow:0 0 15px rgba(102,252,241,0.1);
        }
        
        .question-title{
            color:var(--accent);
            font-weight:700;
            font-size:16px;
            margin-bottom:8px;
            text-shadow:0 0 10px rgba(102,252,241,0.3);
        }
        
        .question-text{
            color:var(--text);
            margin-bottom:12px;
            font-size:14px;
        }
        
        .answers-list{
            display:flex;
            flex-direction:column;
            gap:6px;
            margin-bottom:12px;
        }
        
        .answer-item{
            display:flex;
            justify-content:space-between;
            padding:6px 10px;
            background:rgba(0,0,0,0.3);
            border-radius:4px;
            border:1px solid rgba(102,252,241,0.1);
            font-size:13px;
        }
        
        .answer-text{
            color:var(--text);
        }
        
        .answer-points{
            color:var(--success);
            font-weight:700;
        }
        
        .question-actions{
            display:flex;
            gap:8px;
        }
        
        .new-answer-item{
            display:flex;
            gap:8px;
            margin-bottom:8px;
            align-items:center;
        }
        
        .new-answer-item input[type="text"]{
            flex:1;
        }
        
        .new-answer-item input[type="number"]{
            width:100px;
        }
        
        .new-answer-item button{
            padding:8px 12px;
            font-size:12px;
        }
    </style>
</head>
<body>
<header>
    <h1>📚 Banco de Preguntas</h1>
    <div>
        <a href="/controller" style="color:var(--accent);text-decoration:none;font-weight:700">← Volver al Controller</a>
    </div>
</header>

<main style="max-width:1200px;margin:0 auto">
    <!-- Nueva Pregunta -->
    <section>
        <h3>➕ Nueva Pregunta</h3>
        <div style="margin-bottom:12px">
            <label>Nombre de la pregunta:</label>
            <input type="text" id="newQuestionName" placeholder="Ej: Frutas favoritas" />
        </div>
        <div style="margin-bottom:12px">
            <label>Pregunta:</label>
            <input type="text" id="newQuestionText" placeholder="¿Qué fruta comen más los niños?" />
        </div>
        <div style="margin-bottom:12px">
            <label>Respuestas:</label>
            <div id="newAnswersList"></div>
            <button id="addNewAnswer" class="btn-secondary" style="margin-top:8px">+ Agregar Respuesta</button>
        </div>
        <button id="saveNewQuestion">💾 Guardar Pregunta</button>
    </section>

    <!-- Lista de Preguntas Guardadas -->
    <section>
        <h3>📋 Preguntas Guardadas</h3>
        <div id="questionsList"></div>
        <div id="emptyState" style="text-align:center;padding:40px;color:var(--muted);display:none">
            <div style="font-size:48px;margin-bottom:12px">📝</div>
            <div>No hay preguntas guardadas aún</div>
            <div style="font-size:13px;margin-top:8px">Crea tu primera pregunta arriba</div>
        </div>
    </section>
</main>

<script>
let savedQuestionsData = {};
let newAnswers = [];

// Load saved questions from localStorage
function loadSavedQuestions() {
    try {
        const stored = localStorage.getItem('game-saved-questions');
        if (stored) {
            savedQuestionsData = JSON.parse(stored);
        }
    } catch (e) {
        console.error('Error loading saved questions:', e);
    }
    renderQuestionsList();
}

// Save questions to localStorage
function saveSavedQuestions() {
    try {
        localStorage.setItem('game-saved-questions', JSON.stringify(savedQuestionsData));
    } catch (e) {
        console.error('Error saving questions:', e);
    }
}

// Render new answers list
function renderNewAnswers() {
    const container = document.getElementById('newAnswersList');
    container.innerHTML = '';
    
    newAnswers.forEach((ans, idx) => {
        const div = document.createElement('div');
        div.className = 'new-answer-item';
        div.innerHTML = `
            <input type="text" value="${escapeHtml(ans.text)}" placeholder="Respuesta" data-idx="${idx}" class="answer-text-input" />
            <input type="number" value="${ans.count}" min="0" placeholder="Pts" data-idx="${idx}" class="answer-count-input" />
            <button class="btn-danger" data-idx="${idx}" onclick="removeNewAnswer(${idx})">✕</button>
        `;
        container.appendChild(div);
    });
    
    // Add event listeners
    document.querySelectorAll('.answer-text-input').forEach(input => {
        input.addEventListener('input', (e) => {
            const idx = parseInt(e.target.dataset.idx);
            newAnswers[idx].text = e.target.value;
        });
    });
    
    document.querySelectorAll('.answer-count-input').forEach(input => {
        input.addEventListener('input', (e) => {
            const idx = parseInt(e.target.dataset.idx);
            newAnswers[idx].count = parseInt(e.target.value) || 0;
        });
    });
}

// Remove answer from new question
function removeNewAnswer(idx) {
    newAnswers.splice(idx, 1);
    renderNewAnswers();
}

// Add new answer
document.getElementById('addNewAnswer').addEventListener('click', () => {
    newAnswers.push({text: '', count: 0});
    renderNewAnswers();
});

// Save new question
document.getElementById('saveNewQuestion').addEventListener('click', () => {
    const name = document.getElementById('newQuestionName').value.trim();
    const questionText = document.getElementById('newQuestionText').value.trim();
    
    if (!name) {
        alert('⚠️ Ingresa un nombre para la pregunta');
        return;
    }
    if (!questionText) {
        alert('⚠️ Escribe la pregunta');
        return;
    }
    if (newAnswers.length === 0 || newAnswers.every(a => !a.text.trim())) {
        alert('⚠️ Agrega al menos una respuesta');
        return;
    }
    
    // Filter out empty answers
    const validAnswers = newAnswers.filter(a => a.text.trim());
    
    savedQuestionsData[name] = {
        question: questionText,
        answers: validAnswers
    };
    
    saveSavedQuestions();
    renderQuestionsList();
    
    // Clear form
    document.getElementById('newQuestionName').value = '';
    document.getElementById('newQuestionText').value = '';
    newAnswers = [];
    renderNewAnswers();
    
    alert('✅ Pregunta guardada: ' + name);
});

// Render questions list
function renderQuestionsList() {
    const container = document.getElementById('questionsList');
    const emptyState = document.getElementById('emptyState');
    const keys = Object.keys(savedQuestionsData);
    
    if (keys.length === 0) {
        container.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    container.innerHTML = '';
    
    keys.forEach(name => {
        const data = savedQuestionsData[name];
        const card = document.createElement('div');
        card.className = 'question-card';
        
        let answersHtml = '';
        data.answers.forEach(ans => {
            answersHtml += `
                <div class="answer-item">
                    <span class="answer-text">${escapeHtml(ans.text)}</span>
                    <span class="answer-points">${ans.count} pts</span>
                </div>
            `;
        });
        
        card.innerHTML = `
            <div class="question-title">${escapeHtml(name)}</div>
            <div class="question-text">${escapeHtml(data.question)}</div>
            <div class="answers-list">${answersHtml}</div>
            <div class="question-actions">
                <button onclick="copyToController('${escapeHtml(name)}')">📋 Copiar al Controller</button>
                <button class="btn-danger" onclick="deleteQuestion('${escapeHtml(name)}')">🗑️ Eliminar</button>
            </div>
        `;
        
        container.appendChild(card);
    });
}

// Copy question to controller (via localStorage)
function copyToController(name) {
    const data = savedQuestionsData[name];
    if (!data) {
        alert('⚠️ Pregunta no encontrada');
        return;
    }
    
    // Store in a special key for controller to pick up
    try {
        localStorage.setItem('game-load-question', JSON.stringify(data));
        alert('✅ Pregunta copiada. Abre el Controller y recarga la página para cargarla.');
    } catch (e) {
        alert('❌ Error al copiar: ' + e.message);
    }
}

// Delete question
function deleteQuestion(name) {
    if (!confirm('¿Eliminar la pregunta "' + name + '"?')) {
        return;
    }
    
    delete savedQuestionsData[name];
    saveSavedQuestions();
    renderQuestionsList();
    alert('✅ Pregunta eliminada');
}

// Helper to escape HTML
function escapeHtml(s) {
    if (!s) return '';
    return String(s).replace(/[&<>"']/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c];
    });
}

// Initialize
loadSavedQuestions();
renderNewAnswers();
</script>
</body>
</html>
