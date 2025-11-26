-- =====================================================
-- Esquema SQL de Ejemplo
-- 100 Programadores Dijieron
-- =====================================================
-- Este archivo muestra cómo se verán las tablas en MySQL
-- NO necesitas ejecutar esto, Laravel lo hará automáticamente
-- =====================================================

-- TABLA 1: games
CREATE TABLE `games` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `team1_name` VARCHAR(255) NOT NULL,
  `team2_name` VARCHAR(255) NOT NULL,
  `team1_score` INT NOT NULL DEFAULT 0,
  `team2_score` INT NOT NULL DEFAULT 0,
  `status` ENUM('waiting', 'in_progress', 'finished') NOT NULL DEFAULT 'waiting',
  `started_at` TIMESTAMP NULL,
  `finished_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 2: questions
CREATE TABLE `questions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `question_text` TEXT NOT NULL,
  `category` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `times_used` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 3: answers
CREATE TABLE `answers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `question_id` BIGINT UNSIGNED NOT NULL,
  `answer_text` VARCHAR(255) NOT NULL,
  `points` INT NOT NULL,
  `order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 4: rounds
CREATE TABLE `rounds` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `game_id` BIGINT UNSIGNED NOT NULL,
  `question_id` BIGINT UNSIGNED NOT NULL,
  `round_number` INT NOT NULL,
  `multiplier` INT NOT NULL DEFAULT 1,
  `strikes` INT NOT NULL DEFAULT 0,
  `active_team` VARCHAR(255) NULL,
  `winning_team` VARCHAR(255) NULL,
  `points_awarded` INT NOT NULL DEFAULT 0,
  `status` ENUM('in_progress', 'steal_attempt', 'completed') NOT NULL DEFAULT 'in_progress',
  `started_at` TIMESTAMP NULL,
  `completed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`question_id`) REFERENCES `questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 5: round_answers
CREATE TABLE `round_answers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `round_id` BIGINT UNSIGNED NOT NULL,
  `answer_id` BIGINT UNSIGNED NOT NULL,
  `is_revealed` TINYINT(1) NOT NULL DEFAULT 0,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `revealed_by_team` VARCHAR(255) NULL,
  `revealed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`round_id`) REFERENCES `rounds`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`answer_id`) REFERENCES `answers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 6: game_events
CREATE TABLE `game_events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `game_id` BIGINT UNSIGNED NOT NULL,
  `round_id` BIGINT UNSIGNED NULL,
  `event_type` ENUM(
    'game_started',
    'game_finished',
    'round_started',
    'round_finished',
    'answer_revealed',
    'strike_added',
    'steal_attempt',
    'steal_success',
    'steal_failed',
    'points_awarded'
  ) NOT NULL,
  `team` VARCHAR(255) NULL,
  `event_data` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`round_id`) REFERENCES `rounds`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ÍNDICES PARA MEJORAR RENDIMIENTO
-- =====================================================

-- Índices en rounds
CREATE INDEX idx_game_status ON rounds(game_id, status);
CREATE INDEX idx_round_number ON rounds(game_id, round_number);

-- Índices en round_answers
CREATE INDEX idx_round_revealed ON round_answers(round_id, is_revealed);

-- Índices en questions
CREATE INDEX idx_active_category ON questions(is_active, category);
CREATE INDEX idx_times_used ON questions(times_used);

-- Índices en game_events
CREATE INDEX idx_game_created ON game_events(game_id, created_at);
CREATE INDEX idx_event_type ON game_events(event_type);

-- =====================================================
-- DATOS DE EJEMPLO (1 pregunta con 3 respuestas)
-- =====================================================

-- Insertar pregunta
INSERT INTO `questions` (`name`, `question_text`, `category`, `is_active`) 
VALUES ('Frutas favoritas', '¿Qué fruta comen más los niños?', 'general', 1);

-- Insertar respuestas (asumiendo que question_id = 1)
INSERT INTO `answers` (`question_id`, `answer_text`, `points`, `order`) VALUES
(1, 'Manzana', 32, 1),
(1, 'Plátano', 25, 2),
(1, 'Naranja', 18, 3),
(1, 'Fresa', 12, 4),
(1, 'Uva', 8, 5),
(1, 'Sandía', 5, 6);

-- =====================================================
-- QUERIES DE EJEMPLO
-- =====================================================

-- Obtener todas las preguntas activas con sus respuestas
SELECT 
    q.id,
    q.name,
    q.question_text,
    q.category,
    a.answer_text,
    a.points
FROM questions q
LEFT JOIN answers a ON q.id = a.question_id
WHERE q.is_active = 1
ORDER BY q.id, a.order;

-- Obtener el estado actual de una ronda
SELECT 
    r.id AS round_id,
    r.round_number,
    r.multiplier,
    r.strikes,
    r.active_team,
    q.question_text,
    a.answer_text,
    a.points,
    ra.is_revealed,
    ra.is_correct,
    ra.revealed_by_team
FROM rounds r
JOIN questions q ON r.question_id = q.id
JOIN round_answers ra ON r.id = ra.round_id
JOIN answers a ON ra.answer_id = a.id
WHERE r.id = 1
ORDER BY a.order;

-- Calcular puntos acumulados en una ronda
SELECT 
    r.id,
    r.multiplier,
    SUM(a.points) AS base_points,
    SUM(a.points) * r.multiplier AS final_points
FROM rounds r
JOIN round_answers ra ON r.id = ra.round_id
JOIN answers a ON ra.answer_id = a.id
WHERE r.id = 1 AND ra.is_correct = 1
GROUP BY r.id;

-- Obtener el historial de eventos de una partida
SELECT 
    ge.event_type,
    ge.team,
    ge.event_data,
    ge.created_at,
    r.round_number
FROM game_events ge
LEFT JOIN rounds r ON ge.round_id = r.id
WHERE ge.game_id = 1
ORDER BY ge.created_at DESC;

-- Obtener el marcador actual de una partida
SELECT 
    id,
    name,
    team1_name,
    team1_score,
    team2_name,
    team2_score,
    status
FROM games
WHERE id = 1;

-- Preguntas más usadas
SELECT 
    name,
    question_text,
    category,
    times_used
FROM questions
ORDER BY times_used DESC
LIMIT 10;

-- =====================================================
-- FIN DEL ARCHIVO
-- =====================================================
