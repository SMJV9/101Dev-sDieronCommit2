<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Banco de Preguntas - 1100100 Devs Dijeron</title>
    @vite('resources/css/questions.css')
</head>
<body>
<header>
    <h1>📚 Banco de Preguntas</h1>
    <div style="display:flex;gap:20px;align-items:center">
        <a href="/controller" style="color:var(--accent);text-decoration:none;font-weight:700">← Volver al Controller</a>
    </div>
</header>

<main style="max-width:1200px;margin:0 auto">
    <!-- Filtros -->
    <section>
        <h3>🔍 Filtros</h3>
        <div style="display:flex;justify-content:flex-start;align-items:center;margin-bottom:16px;gap:20px">
            <div>
                <label>Filtrar por Tipo:</label>
                <select id="typeFilter" style="width:200px">
                    <option value="all">🎯 Todos los Tipos</option>
                    <option value="round">🎯 Solo Rondas</option>
                    <option value="fast_money">💰 Solo Dinero Rápido</option>
                </select>
            </div>
            <div>
                <label>Filtrar por Categoría:</label>
                <select id="categoryFilter" style="width:200px">
                    <option value="all">📚 Todas las Categorías</option>
                    <option value="general">🌍 General</option>
                    <option value="dev">💻 Desarrollo/Programación</option>
                </select>
            </div>
        </div>
    </section>

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
            <label>Categoría:</label>
            <select id="newQuestionCategory" style="width:100%">
                <option value="general">General</option>
                <option value="dev">Desarrollo/Programación</option>
            </select>
        </div>
        <div style="margin-bottom:12px">
            <label>Tipo de Pregunta:</label>
            <select id="newQuestionType" style="width:100%">
                <option value="round">🎯 Ronda Normal (Para juego principal)</option>
                <option value="fast_money">💰 Dinero Rápido (Para ronda especial)</option>
            </select>
            <div style="font-size:0.85rem;color:var(--muted);margin-top:4px;padding:8px;background:rgba(102,252,241,0.05);border-radius:4px;">
                <strong>Ronda Normal:</strong> Preguntas para las 3 rondas principales del juego<br>
                <strong>Dinero Rápido:</strong> Preguntas especiales para la ronda final de dinero rápido
            </div>
        </div>
        <div style="margin-bottom:12px">
            <label>Respuestas:</label>
            <div id="newAnswersList"></div>
            <button id="addNewAnswer" class="btn-secondary" style="margin-top:8px">+ Agregar Respuesta</button>
        </div>
        <button id="saveNewQuestion">💾 Guardar Pregunta</button>
        <button id="cancelEdit" class="btn-secondary">✖ Cancelar</button>
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
let editingQuestionId = null;

// Load saved questions from DATABASE via API
function loadSavedQuestions() {
    fetch('/api/questions')
        .then(response => response.json())
        .then(questions => {
            savedQuestionsData = {};
            questions.forEach(q => {
                savedQuestionsData[q.id] = {
                    id: q.id,
                    name: q.name,
                    question: q.question_text,
                    category: q.category,
                    question_type: q.question_type || 'round',
                    is_active: q.is_active,
                    times_used: q.times_used,
                    answers: q.answers.map(a => ({
                        text: a.answer_text,
                        count: a.points
                    }))
                };
            });
            renderQuestionsList();
        })
        .catch(error => {
            console.error('Error loading questions:', error);
            alert('❌ Error al cargar las preguntas');
        });
}

// Render questions list - simple list without grouping
function renderQuestionsList(keys = null) {
    const container = document.getElementById('questionsList');
    const emptyState = document.getElementById('emptyState');
    const questionKeys = keys || Object.keys(savedQuestionsData);
    
    if (questionKeys.length === 0) {
        container.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    container.innerHTML = '';
    
    // Simply render all questions in a list
    questionKeys.forEach(id => {
        const questionCard = createQuestionCard(id);
        container.appendChild(questionCard);
    });
}

// Create individual question card
function createQuestionCard(id) {
    const data = savedQuestionsData[id];
    const card = document.createElement('div');
    card.className = 'question-card' + (data.is_active ? '' : ' inactive-question');
        
    let answersHtml = '';
    // Sort answers by points (highest to lowest)
    const sortedAnswers = [...data.answers].sort((a, b) => b.count - a.count);
    sortedAnswers.forEach(ans => {
        answersHtml += `
            <div class="answer-item">
                <span class="answer-text">${escapeHtml(ans.text)}</span>
                <span class="answer-points">${ans.count} pts</span>
            </div>
        `;
    });
    
    const activeButton = data.is_active 
        ? '<button class="btn-secondary" onclick="toggleActive(' + id + ')">🔒 Desactivar</button>'
        : '<button class="btn-success" onclick="toggleActive(' + id + ')">✅ Activar</button>';
    
    const categoryName = data.category === 'dev' ? 'Desarrollo' : 'General';
    
    card.innerHTML = `
        <div class="question-header" onclick="toggleQuestionDetails(${id})">
            <div class="question-title-row">
                <div class="question-title">
                    ${escapeHtml(data.name)} 
                    ${data.is_active ? '<span class="badge-active">✓ Activa</span>' : '<span class="badge-inactive">✗ Inactiva</span>'}
                </div>
                <div class="expand-icon">▼</div>
            </div>
            <div class="question-meta">
                <span class="category-badge">${categoryName}</span>
                <span class="type-badge ${data.question_type === 'fast_money' ? 'type-fast-money' : 'type-round'}">${data.question_type === 'fast_money' ? '⚡ Dinero Rápido' : '🎯 Ronda'}</span>
                <span class="usage-count">Usada ${data.times_used} veces</span>
            </div>
        </div>
        <div class="question-details" id="details-${id}" style="display: none;">
            <div class="question-text">${escapeHtml(data.question)}</div>
            <div class="answers-list">${answersHtml}</div>
            <div class="question-actions">
                <button onclick="loadToController(${id})">📋 Cargar al Controller</button>
                <button onclick="editQuestion(${id})">✏️ Editar</button>
                ${activeButton}
                <button class="btn-danger" onclick="deleteQuestion(${id})">🗑️ Eliminar</button>
            </div>
        </div>
    `;
    
    return card;
}

// Filter questions based on selected filters
function filterQuestions() {
    const typeFilter = document.getElementById('typeFilter').value;
    const categoryFilter = document.getElementById('categoryFilter').value;
    
    const keys = Object.keys(savedQuestionsData);
    const filteredKeys = keys.filter(id => {
        const data = savedQuestionsData[id];
        
        // Type filter
        if (typeFilter !== 'all') {
            if (data.question_type !== typeFilter) return false;
        }
        
        // Category filter
        if (categoryFilter !== 'all') {
            if (data.category !== categoryFilter) return false;
        }
        
        return true;
    });
    
    renderQuestionsList(filteredKeys);
}

// Add new answer to the list
function addNewAnswer() {
    const text = prompt('Texto de la respuesta:');
    if (!text) return;
    
    const count = parseInt(prompt('Puntos de la respuesta:', '10'));
    if (isNaN(count)) return;
    
    newAnswers.push({ text, count });
    renderNewAnswers();
}

// Render new answers list
function renderNewAnswers() {
    const container = document.getElementById('newAnswersList');
    container.innerHTML = '';
    
    newAnswers.forEach((ans, index) => {
        const answerDiv = document.createElement('div');
        answerDiv.className = 'new-answer-item';
        answerDiv.innerHTML = `
            <span>${escapeHtml(ans.text)} (${ans.count} pts)</span>
            <button onclick="removeNewAnswer(${index})" style="background:var(--danger);margin-left:10px">Eliminar</button>
        `;
        container.appendChild(answerDiv);
    });
}

// Remove answer from new answers list
function removeNewAnswer(index) {
    newAnswers.splice(index, 1);
    renderNewAnswers();
}

// Save new question
function saveNewQuestion() {
    const name = document.getElementById('newQuestionName').value.trim();
    const question = document.getElementById('newQuestionText').value.trim();
    const category = document.getElementById('newQuestionCategory').value;
    const questionType = document.getElementById('newQuestionType').value;
    
    if (!name || !question) {
        alert('Completa el nombre y la pregunta');
        return;
    }
    
    if (newAnswers.length === 0) {
        alert('Agrega al menos una respuesta');
        return;
    }
    
    const questionData = {
        name: name,
        question_text: question,
        category: category,
        question_type: questionType,
        answers: newAnswers
    };
    
    const url = editingQuestionId ? `/api/questions/${editingQuestionId}` : '/api/questions';
    const method = editingQuestionId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(questionData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.question) {
            alert(editingQuestionId ? '✅ Pregunta actualizada' : '✅ Pregunta guardada');
            clearForm();
            loadSavedQuestions();
        } else {
            alert('❌ Error al guardar: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al guardar la pregunta');
    });
}

// Clear form
function clearForm() {
    document.getElementById('newQuestionName').value = '';
    document.getElementById('newQuestionText').value = '';
    document.getElementById('newQuestionCategory').value = 'general';
    document.getElementById('newQuestionType').value = 'round';
    newAnswers = [];
    editingQuestionId = null;
    renderNewAnswers();
    
    document.getElementById('saveNewQuestion').textContent = '💾 Guardar Pregunta';
    document.getElementById('cancelEdit').style.display = 'none';
}

// Edit question
function editQuestion(id) {
    const data = savedQuestionsData[id];
    editingQuestionId = id;
    
    document.getElementById('newQuestionName').value = data.name;
    document.getElementById('newQuestionText').value = data.question;
    document.getElementById('newQuestionCategory').value = data.category;
    document.getElementById('newQuestionType').value = data.question_type || 'round';
    
    newAnswers = [...data.answers];
    renderNewAnswers();
    
    document.getElementById('saveNewQuestion').textContent = '💾 Actualizar Pregunta';
    document.getElementById('cancelEdit').style.display = 'inline-block';
}

// Delete question
function deleteQuestion(id) {
    if (!confirm('¿Seguro que quieres eliminar esta pregunta?')) return;
    
    fetch(`/api/questions/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Pregunta eliminada');
            loadSavedQuestions();
        } else {
            alert('❌ Error al eliminar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al eliminar la pregunta');
    });
}

// Toggle question active status
function toggleActive(id) {
    fetch(`/api/questions/${id}/toggle-active`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadSavedQuestions();
        } else {
            alert('❌ Error al cambiar estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al cambiar estado');
    });
}

// Toggle question details visibility
function toggleQuestionDetails(id) {
    const details = document.getElementById(`details-${id}`);
    const icon = document.querySelector(`[onclick="toggleQuestionDetails(${id})"] .expand-icon`);
    
    if (details.style.display === 'none') {
        details.style.display = 'block';
        icon.textContent = '▲';
    } else {
        details.style.display = 'none';
        icon.textContent = '▼';
    }
}

// Load question to controller
function loadToController(id) {
    window.open(`/controller?load=${id}`, '_blank');
}

// Escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Event listeners
document.getElementById('addNewAnswer').addEventListener('click', addNewAnswer);
document.getElementById('saveNewQuestion').addEventListener('click', saveNewQuestion);
document.getElementById('cancelEdit').addEventListener('click', clearForm);
document.getElementById('typeFilter').addEventListener('change', filterQuestions);
document.getElementById('categoryFilter').addEventListener('change', filterQuestions);

// Initialize
loadSavedQuestions();
renderNewAnswers();
</script>
</body>
</html>