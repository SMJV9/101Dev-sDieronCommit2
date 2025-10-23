# 🗄️ Estructura de Base de Datos - 100 Programadores Dijieron

## 📋 Descripción General

Este documento describe la estructura de la base de datos para el juego "100 Programadores Dijieron" (tipo Family Feud).

## 🎯 Tablas Principales

### 1️⃣ **games** - Partidas del Juego
Almacena información de cada partida completa.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único de la partida |
| `name` | string | Nombre/identificador de la partida |
| `team1_name` | string | Nombre del Equipo 1 |
| `team2_name` | string | Nombre del Equipo 2 |
| `team1_score` | integer | Puntuación total del Equipo 1 |
| `team2_score` | integer | Puntuación total del Equipo 2 |
| `status` | enum | Estado: `waiting`, `in_progress`, `finished` |
| `started_at` | timestamp | Cuándo inició la partida |
| `finished_at` | timestamp | Cuándo terminó la partida |

**Relaciones:**
- `hasMany` → `rounds`
- `hasMany` → `game_events`
- `hasOne` → `currentRound` (ronda activa)

---

### 2️⃣ **questions** - Banco de Preguntas
Almacena todas las preguntas disponibles para el juego.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único de la pregunta |
| `name` | string | Nombre corto/identificador único |
| `question_text` | text | La pregunta completa |
| `category` | string | Categoría (dev, general, etc.) |
| `is_active` | boolean | Si está disponible para usar |
| `times_used` | integer | Contador de veces usada |

**Relaciones:**
- `hasMany` → `answers`
- `hasMany` → `rounds`

**Ejemplo:**
```
name: "Lenguajes de programación"
question_text: "¿Cuál es el primer lenguaje que aprenden los programadores?"
category: "dev"
```

---

### 3️⃣ **answers** - Respuestas de las Preguntas
Almacena las respuestas posibles para cada pregunta.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único de la respuesta |
| `question_id` | foreignId | Pregunta a la que pertenece |
| `answer_text` | string | Texto de la respuesta |
| `points` | integer | Puntos que vale esta respuesta |
| `order` | integer | Orden en que aparece (1-6) |

**Relaciones:**
- `belongsTo` → `question`
- `hasMany` → `round_answers`

**Ejemplo:**
```
question_id: 2
answer_text: "Python"
points: 40
order: 1
```

---

### 4️⃣ **rounds** - Rondas del Juego
Cada ronda dentro de una partida.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único de la ronda |
| `game_id` | foreignId | Partida a la que pertenece |
| `question_id` | foreignId | Pregunta de esta ronda |
| `round_number` | integer | Número de ronda (1, 2, 3...) |
| `multiplier` | integer | Multiplicador de puntos (1x, 2x, 3x) |
| `strikes` | integer | Número de X's (errores) |
| `active_team` | string | Equipo que tiene el turno actual |
| `winning_team` | string | Equipo que ganó la ronda |
| `points_awarded` | integer | Puntos otorgados al final |
| `status` | enum | `in_progress`, `steal_attempt`, `completed` |
| `started_at` | timestamp | Inicio de la ronda |
| `completed_at` | timestamp | Fin de la ronda |

**Relaciones:**
- `belongsTo` → `game`
- `belongsTo` → `question`
- `hasMany` → `round_answers`
- `hasMany` → `game_events`

**Métodos útiles:**
- `calculateAccumulatedPoints()` - Suma los puntos de respuestas correctas
- `addStrike()` - Añade una X y cambia a `steal_attempt` si llega a 3

---

### 5️⃣ **round_answers** - Estado de Respuestas en Ronda
Tracking de cada respuesta durante una ronda específica.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único |
| `round_id` | foreignId | Ronda a la que pertenece |
| `answer_id` | foreignId | Respuesta original |
| `is_revealed` | boolean | Si ya fue revelada |
| `is_correct` | boolean | Si fue acertada por algún equipo |
| `revealed_by_team` | string | Qué equipo la reveló |
| `revealed_at` | timestamp | Cuándo fue revelada |

**Relaciones:**
- `belongsTo` → `round`
- `belongsTo` → `answer`

**Métodos útiles:**
- `reveal($team)` - Revela la respuesta
- `markAsCorrect($team)` - Marca como correcta y suma puntos

---

### 6️⃣ **game_events** - Registro de Eventos
Log de todos los eventos importantes del juego.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único |
| `game_id` | foreignId | Partida relacionada |
| `round_id` | foreignId | Ronda relacionada (nullable) |
| `event_type` | enum | Tipo de evento (ver abajo) |
| `team` | string | Equipo involucrado (nullable) |
| `event_data` | json | Datos adicionales del evento |

**Tipos de eventos:**
- `game_started` - Partida iniciada
- `game_finished` - Partida terminada
- `round_started` - Ronda iniciada
- `round_finished` - Ronda terminada
- `answer_revealed` - Respuesta revelada
- `strike_added` - X añadida
- `steal_attempt` - Intento de robo de puntos
- `steal_success` - Robo exitoso
- `steal_failed` - Robo fallido
- `points_awarded` - Puntos asignados

**Relaciones:**
- `belongsTo` → `game`
- `belongsTo` → `round`

**Método estático:**
```php
GameEvent::logEvent($gameId, 'answer_revealed', $roundId, 'Equipo 1', [
    'answer' => 'Python',
    'points' => 40
]);
```

---

## 🔄 Flujo de Datos Típico

### Iniciar una partida:
1. Crear registro en `games` con ambos equipos
2. Crear registro en `game_events` tipo `game_started`

### Iniciar una ronda:
1. Crear registro en `rounds` con la pregunta y multiplicador
2. Crear registros en `round_answers` para cada respuesta de la pregunta
3. Crear evento `round_started`
4. Incrementar `times_used` en `questions`

### Durante la ronda:
1. Cuando alguien acierta → `markAsCorrect()` en `round_answers`
2. Cuando hay error → `addStrike()` en `rounds`
3. Cuando llega a 3 strikes → cambiar status a `steal_attempt`

### Robo de puntos:
1. Crear evento `steal_attempt`
2. Si acierta → evento `steal_success` + asignar puntos
3. Si falla → evento `steal_failed` + asignar al equipo original

### Finalizar ronda:
1. Calcular puntos totales con `calculateAccumulatedPoints()`
2. Aplicar multiplicador
3. Asignar a `winning_team` y `points_awarded`
4. Actualizar `team1_score` o `team2_score` en `games`
5. Cambiar status a `completed`
6. Crear evento `round_finished` y `points_awarded`

---

## 🚀 Comandos de Migración

```bash
# Ejecutar las migraciones
php artisan migrate

# Ejecutar seeders con datos de ejemplo
php artisan db:seed

# O ambos a la vez
php artisan migrate:fresh --seed

# Solo el seeder de preguntas
php artisan db:seed --class=QuestionSeeder
```

---

## 📊 Queries Útiles

### Obtener ronda actual de una partida:
```php
$game = Game::with('currentRound')->find($id);
$currentRound = $game->currentRound;
```

### Obtener todas las respuestas de una ronda con puntos:
```php
$round = Round::with('roundAnswers.answer')->find($id);
$answers = $round->roundAnswers;
```

### Calcular puntos acumulados en ronda:
```php
$totalPoints = $round->calculateAccumulatedPoints();
$finalPoints = $totalPoints * $round->multiplier;
```

### Obtener historial de eventos de una partida:
```php
$events = GameEvent::where('game_id', $gameId)
    ->orderBy('created_at', 'desc')
    ->get();
```

### Obtener preguntas más usadas:
```php
$popular = Question::where('is_active', true)
    ->orderBy('times_used', 'desc')
    ->take(10)
    ->get();
```

---

## 💡 Datos de Ejemplo Incluidos

El `QuestionSeeder` incluye 6 preguntas con sus respuestas:

1. **Frutas favoritas** (general)
2. **Lenguajes de programación** (dev)
3. **Editores de código** (dev)
4. **Frameworks JavaScript** (dev)
5. **Sistemas operativos** (dev)
6. **Comida rápida** (general)

Cada pregunta tiene 6 respuestas con puntos del 1 al 50.

---

## 🎮 Integración con la Aplicación Actual

La aplicación actualmente usa `localStorage` y `BroadcastChannel`. Para integrar con la base de datos:

1. Crear API endpoints en Laravel (routes/api.php)
2. Usar Broadcasting (Laravel Echo) para sincronización en tiempo real
3. Reemplazar localStorage con llamadas a la API
4. Mantener el mismo flujo de mensajes pero persistiendo en BD

**Próximos pasos sugeridos:**
- Crear controladores API para CRUD de preguntas
- Implementar WebSockets con Laravel Echo
- Crear sistema de autenticación para moderadores
- Dashboard de administración de preguntas
