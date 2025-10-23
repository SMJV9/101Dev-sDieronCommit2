# 📐 Diagrama de Relaciones de Base de Datos

## Estructura Visual

```
┌─────────────────────────────────────────────────────────────────┐
│                         GAMES (Partidas)                        │
│  • id                                                           │
│  • name                                                         │
│  • team1_name, team2_name                                       │
│  • team1_score, team2_score                                     │
│  • status (waiting/in_progress/finished)                        │
│  • started_at, finished_at                                      │
└──────────────────┬──────────────────┬───────────────────────────┘
                   │                  │
                   │ hasMany          │ hasMany
                   ▼                  ▼
    ┌──────────────────────┐   ┌────────────────────┐
    │  ROUNDS (Rondas)     │   │   GAME_EVENTS      │
    │  • id                │   │   • id             │
    │  • game_id ────FK───▶│   │   • game_id ──FK──▶│
    │  • question_id       │   │   • round_id       │
    │  • round_number      │   │   • event_type     │
    │  • multiplier (1-3x) │   │   • team           │
    │  • strikes (0-3)     │   │   • event_data     │
    │  • active_team       │   └────────────────────┘
    │  • winning_team      │
    │  • points_awarded    │
    │  • status            │
    └──┬────────────┬──────┘
       │            │
       │ belongsTo  │ hasMany
       │            ▼
       │     ┌────────────────────┐
       │     │  ROUND_ANSWERS     │
       │     │  • id              │
       │     │  • round_id ──FK──▶│
       │     │  • answer_id       │
       │     │  • is_revealed     │
       │     │  • is_correct      │
       │     │  • revealed_by_team│
       │     │  • revealed_at     │
       │     └──────┬─────────────┘
       │            │
       │            │ belongsTo
       │            ▼
       │     ┌────────────────────┐
       │     │   ANSWERS          │
       │     │   • id             │
       │     │   • question_id ───┼──┐
       │     │   • answer_text    │  │
       │     │   • points         │  │
       │     │   • order          │  │
       │     └────────────────────┘  │
       │                             │
       │ belongsTo                   │ hasMany
       ▼                             │
┌────────────────────┐               │
│   QUESTIONS        │◀──────────────┘
│   • id             │
│   • name           │
│   • question_text  │
│   • category       │
│   • is_active      │
│   • times_used     │
└────────────────────┘
```

## 🔗 Relaciones Detalladas

### GAMES (1) ──< ROUNDS (N)
Una partida tiene múltiples rondas
```php
// En Game.php
public function rounds() {
    return $this->hasMany(Round::class);
}

// En Round.php
public function game() {
    return $this->belongsTo(Game::class);
}
```

### GAMES (1) ──< GAME_EVENTS (N)
Una partida tiene múltiples eventos
```php
// En Game.php
public function events() {
    return $this->hasMany(GameEvent::class);
}

// En GameEvent.php
public function game() {
    return $this->belongsTo(Game::class);
}
```

### QUESTIONS (1) ──< ANSWERS (N)
Una pregunta tiene múltiples respuestas
```php
// En Question.php
public function answers() {
    return $this->hasMany(Answer::class);
}

// En Answer.php
public function question() {
    return $this->belongsTo(Question::class);
}
```

### QUESTIONS (1) ──< ROUNDS (N)
Una pregunta puede usarse en múltiples rondas
```php
// En Question.php
public function rounds() {
    return $this->hasMany(Round::class);
}

// En Round.php
public function question() {
    return $this->belongsTo(Question::class);
}
```

### ROUNDS (1) ──< ROUND_ANSWERS (N)
Una ronda tiene múltiples respuestas tracked
```php
// En Round.php
public function roundAnswers() {
    return $this->hasMany(RoundAnswer::class);
}

// En RoundAnswer.php
public function round() {
    return $this->belongsTo(Round::class);
}
```

### ANSWERS (1) ──< ROUND_ANSWERS (N)
Una respuesta puede estar en múltiples rondas
```php
// En Answer.php
public function roundAnswers() {
    return $this->hasMany(RoundAnswer::class);
}

// En RoundAnswer.php
public function answer() {
    return $this->belongsTo(Answer::class);
}
```

### ROUNDS (1) ──< GAME_EVENTS (N) [Opcional]
Una ronda puede tener múltiples eventos
```php
// En Round.php
public function events() {
    return $this->hasMany(GameEvent::class);
}

// En GameEvent.php
public function round() {
    return $this->belongsTo(Round::class);
}
```

---

## 🎯 Cardinalidad

| Tabla Principal | Relación | Tabla Relacionada | Tipo |
|----------------|----------|-------------------|------|
| games | 1:N | rounds | Una partida → muchas rondas |
| games | 1:N | game_events | Una partida → muchos eventos |
| questions | 1:N | answers | Una pregunta → muchas respuestas |
| questions | 1:N | rounds | Una pregunta → muchas rondas (reuso) |
| rounds | 1:N | round_answers | Una ronda → muchas respuestas tracked |
| rounds | 1:N | game_events | Una ronda → muchos eventos |
| answers | 1:N | round_answers | Una respuesta → muchas apariciones |

---

## 📋 Ejemplo de Datos Conectados

```
GAME #1 "Partido del Viernes"
├─ team1_name: "Los Debuggers"
├─ team2_name: "Los Compiladores"
├─ status: "in_progress"
│
├─ ROUND #1
│  ├─ question_id: 2 → "¿Cuál es el primer lenguaje?"
│  ├─ round_number: 1
│  ├─ multiplier: 1
│  ├─ active_team: "Los Debuggers"
│  │
│  ├─ ROUND_ANSWERS
│  │  ├─ answer_id: 7 → "Python" (40 pts) ✓ revealed, correct
│  │  ├─ answer_id: 8 → "JavaScript" (28 pts) ✓ revealed
│  │  ├─ answer_id: 9 → "Java" (15 pts) ✗ locked
│  │  ├─ answer_id: 10 → "C++" (10 pts) ✗ locked
│  │  └─ ...
│  │
│  └─ GAME_EVENTS
│     ├─ "round_started" at 20:30
│     ├─ "answer_revealed" → Python by "Los Debuggers"
│     ├─ "strike_added" → team: "Los Debuggers"
│     └─ "answer_revealed" → JavaScript by "Los Compiladores"
│
└─ ROUND #2
   ├─ question_id: 3 → "¿Qué editor de código?"
   ├─ round_number: 2
   ├─ multiplier: 2  (DOBLE!)
   └─ ...
```

---

## 🔑 Claves Foráneas (Foreign Keys)

### En tabla ROUNDS:
- `game_id` → games.id (onDelete: cascade)
- `question_id` → questions.id (onDelete: cascade)

### En tabla ANSWERS:
- `question_id` → questions.id (onDelete: cascade)

### En tabla ROUND_ANSWERS:
- `round_id` → rounds.id (onDelete: cascade)
- `answer_id` → answers.id (onDelete: cascade)

### En tabla GAME_EVENTS:
- `game_id` → games.id (onDelete: cascade)
- `round_id` → rounds.id (onDelete: cascade) [nullable]

---

## 🗂️ Índices Recomendados

Para mejorar el rendimiento:

```sql
-- En rounds
INDEX idx_game_status ON rounds(game_id, status);
INDEX idx_round_number ON rounds(game_id, round_number);

-- En round_answers
INDEX idx_round_revealed ON round_answers(round_id, is_revealed);

-- En questions
INDEX idx_active_category ON questions(is_active, category);
INDEX idx_times_used ON questions(times_used);

-- En game_events
INDEX idx_game_created ON game_events(game_id, created_at);
INDEX idx_event_type ON game_events(event_type);
```

Puedes añadirlos en las migraciones con:
```php
$table->index(['game_id', 'status'], 'idx_game_status');
```
