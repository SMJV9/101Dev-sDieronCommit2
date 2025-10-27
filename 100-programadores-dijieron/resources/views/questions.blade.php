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
            <label>Categoría:</label>
            <select id="newQuestionCategory" style="width:100%">
                <option value="general">General</option>
                <option value="dev">Desarrollo/Programación</option>
            </select>
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
let editingQuestionId = null; // Track if we're editing

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

// Save questions to DATABASE via API
function saveSavedQuestions() {
    // This is handled by individual API calls (store/update)
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

// Save new question or update existing
document.getElementById('saveNewQuestion').addEventListener('click', () => {
    const name = document.getElementById('newQuestionName').value.trim();
    const questionText = document.getElementById('newQuestionText').value.trim();
    const category = document.getElementById('newQuestionCategory').value; // Ya es el value del select
    
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
    
    // Filter out empty answers and sort by points (descending)
    const validAnswers = newAnswers
        .filter(a => a.text.trim())
        .sort((a, b) => b.count - a.count) // Ordenar de mayor a menor
        .map(a => ({
            text: a.text.trim(),
            points: a.count
        }));
    
    const data = {
        name: name,
        question_text: questionText,
        category: category,
        answers: validAnswers
    };
    
    const url = editingQuestionId ? `/api/questions/${editingQuestionId}` : '/api/questions';
    const method = editingQuestionId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(editingQuestionId ? '✅ Pregunta actualizada: ' + name : '✅ Pregunta guardada: ' + name);
            loadSavedQuestions();
            clearForm();
        } else {
            alert('❌ Error: ' + JSON.stringify(result.error));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al guardar la pregunta');
    });
});

// Clear form
function clearForm() {
    document.getElementById('newQuestionName').value = '';
    document.getElementById('newQuestionText').value = '';
    document.getElementById('newQuestionCategory').value = 'general'; // Reset al valor por defecto
    newAnswers = [];
    editingQuestionId = null;
    renderNewAnswers();
    document.getElementById('saveNewQuestion').textContent = '💾 Guardar Pregunta';
    document.getElementById('cancelEdit').style.display = 'none';
}

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
    
    keys.forEach(id => {
        const data = savedQuestionsData[id];
        const card = document.createElement('div');
        card.className = 'question-card' + (data.is_active ? '' : ' inactive-question');
        
        let answersHtml = '';
        // Ordenar respuestas por puntos (mayor a menor) para mostrar
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
        
        // Mostrar nombre legible de categoría
        const categoryName = data.category === 'dev' ? 'Desarrollo' : 'General';
        
        card.innerHTML = `
            <div class="question-title">
                ${escapeHtml(data.name)} 
                ${data.is_active ? '<span class="badge-active">✓ Activa</span>' : '<span class="badge-inactive">✗ Inactiva</span>'}
            </div>
            <div class="question-meta">
                <span class="category-badge">${categoryName}</span>
                <span class="usage-count">Usada ${data.times_used} veces</span>
            </div>
            <div class="question-text">${escapeHtml(data.question)}</div>
            <div class="answers-list">${answersHtml}</div>
            <div class="question-actions">
                <button onclick="loadToController(${id})">📋 Cargar al Controller</button>
                <button onclick="editQuestion(${id})">✏️ Editar</button>
                ${activeButton}
                <button class="btn-danger" onclick="deleteQuestion(${id})">🗑️ Eliminar</button>
            </div>
        `;
        
        container.appendChild(card);
    });
}

// Load question to controller
function loadToController(id) {
    fetch(`/api/questions/${id}/load`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Store in localStorage for controller to pick up
                const controllerData = {
                    question: result.question,
                    answers: result.answers
                };
                localStorage.setItem('game-load-question', JSON.stringify(controllerData));
                alert('✅ Pregunta cargada. Abre el Controller para usarla.');
                // Optionally redirect to controller
                // window.location.href = '/controller';
            } else {
                alert('❌ Error al cargar la pregunta');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al cargar la pregunta');
        });
}

// Edit question
function editQuestion(id) {
    const data = savedQuestionsData[id];
    if (!data) return;
    
    // Fill form with existing data
    document.getElementById('newQuestionName').value = data.name;
    document.getElementById('newQuestionText').value = data.question;
        document.getElementById('newQuestionCategory').value = data.category; // Funciona con el select
    
        // Load answers ordenadas por puntos (mayor a menor)
        newAnswers = [...data.answers]
            .sort((a, b) => b.count - a.count)
            .map(a => ({text: a.text, count: a.count}));
    renderNewAnswers();
    
    // Set editing mode
    editingQuestionId = id;
    document.getElementById('saveNewQuestion').textContent = '💾 Actualizar Pregunta';
    document.getElementById('cancelEdit').style.display = 'inline-block';
    
    // Scroll to top
    window.scrollTo({top: 0, behavior: 'smooth'});
}

// Cancel edit
document.getElementById('cancelEdit').addEventListener('click', clearForm);

// Toggle active status
function toggleActive(id) {
    fetch(`/api/questions/${id}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            loadSavedQuestions();
        } else {
            alert('❌ Error al cambiar el estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al cambiar el estado');
    });
}

// Delete question
function deleteQuestion(id) {
    const data = savedQuestionsData[id];
    if (!confirm('¿Eliminar la pregunta "' + data.name + '"?')) {
        return;
    }
    
    fetch(`/api/questions/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('✅ ' + result.message);
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
