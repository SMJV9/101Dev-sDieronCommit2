# 🚀 Guía Rápida de Uso - Sistema CRUD de Preguntas

## ▶️ Iniciar el Servidor

```bash
cd "c:\Users\vasqu\OneDrive\Escritorio\alexpelon\100-programadores-dijieron"
php artisan serve
```

Luego abre: **http://localhost:8000**

---

## 📋 Rutas Principales

| Ruta | Descripción |
|------|-------------|
| `/` | Página de inicio |
| `/controller` | Panel de control del juego |
| `/tablero` | Tablero del juego |
| `/questions` | **Banco de preguntas (CRUD)** |

---

## 🎯 Flujo de Trabajo Típico

### 1. Crear una Pregunta Nueva
1. Ir a: **http://localhost:8000/questions**
2. Llenar el formulario:
   - **Nombre**: "Colores favoritos" (único)
   - **Pregunta**: "¿Qué color prefieren los programadores?"
   - **Categoría**: "general"
3. Click "**+ Agregar Respuesta**" (mínimo 6 para el tablero)
4. Para cada respuesta:
   - Texto: "Azul"
   - Puntos: 35
5. Click "**💾 Guardar Pregunta**"

### 2. Editar una Pregunta
1. En el banco de preguntas, busca tu pregunta
2. Click "**✏️ Editar**"
3. El formulario se llena automáticamente
4. Modifica lo que necesites
5. Click "**💾 Actualizar Pregunta**"
6. O "**✖ Cancelar**" para abortar

### 3. Cargar al Controller y Jugar
1. En el banco de preguntas, click "**📋 Cargar al Controller**"
2. Abrir en otra pestaña: **http://localhost:8000/controller**
3. La pregunta aparece automáticamente cargada
4. Click "**Enviar al tablero**" (o ya está enviada automáticamente)
5. Abrir en otra pestaña: **http://localhost:8000/tablero**
6. ¡La pregunta aparece en el tablero!

---

## 🎮 Cómo Jugar

### Desde el Controller:
1. Cargar pregunta (desde banco o manual)
2. Configurar equipos (Equipo 1 y Equipo 2)
3. Elegir multiplicador (x1, x2, x3)
4. Click "**Iniciar ronda**" (cuenta regresiva 5-4-3-2-1)
5. Seleccionar equipo activo
6. Click "**Revelar**" en cada respuesta cuando acierten
7. Click "**Acierto**" para sumar puntos
8. Click "**❌ X**" cuando fallen (máximo 3)
9. Si llegan a 3 X's, el otro equipo puede robar

### Desde el Tablero:
- Ver preguntas en tiempo real
- Ver respuestas reveladas
- Ver puntajes de equipos
- Ver multiplicador de ronda
- Animaciones de aciertos y errores

---

## 🗂️ Gestión de Preguntas

### Estados:
- ✅ **Activa** (verde) - Disponible para usar
- ❌ **Inactiva** (rojo, opaca) - No disponible

### Acciones:
- **📋 Cargar al Controller** - Usar en el juego
- **✏️ Editar** - Modificar pregunta/respuestas
- **🔒 Desactivar** - Ocultar sin eliminar
- **✅ Activar** - Volver a habilitar
- **🗑️ Eliminar** - Borrar permanentemente (confirma primero)

---

## 📊 Ver Datos en MySQL

```sql
-- Conectarse a MySQL
mysql -u root -p

-- Usar la base de datos
USE devdijieron;

-- Ver todas las preguntas
SELECT id, name, question_text, category, is_active, times_used 
FROM questions;

-- Ver respuestas de una pregunta específica
SELECT a.order, a.answer_text, a.points 
FROM answers a 
WHERE a.question_id = 1 
ORDER BY a.order;

-- Preguntas más usadas
SELECT name, times_used 
FROM questions 
ORDER BY times_used DESC 
LIMIT 5;

-- Preguntas por categoría
SELECT category, COUNT(*) as total 
FROM questions 
GROUP BY category;
```

---

## 🔧 Solución de Problemas

### Error: "CSRF token mismatch"
**Solución:**
```bash
php artisan config:clear
php artisan cache:clear
```
Recargar la página.

### Las preguntas no se guardan
**Verificar:**
1. Base de datos está corriendo: `mysql -u root -p`
2. Conexión en `.env` está correcta
3. Migraciones ejecutadas: `php artisan migrate:status`

### Pregunta cargada pero no aparece en tablero
**Solución:**
1. Asegúrate de tener ambas ventanas abiertas
2. En controller, click "Enviar al tablero"
3. Refrescar la página del tablero (F5)

### No aparecen las 6 preguntas de ejemplo
**Ejecutar:**
```bash
php artisan db:seed --class=QuestionSeeder
```

---

## 🎨 Ejemplos de Preguntas

### Pregunta Desarrollo:
```
Nombre: Errores comunes
Pregunta: ¿Qué error ves más en tu código?
Categoría: dev

Respuestas:
1. Syntax Error - 40 pts
2. Null Pointer Exception - 30 pts
3. Index Out of Bounds - 15 pts
4. Undefined Variable - 8 pts
5. Type Error - 5 pts
6. Stack Overflow - 2 pts
```

### Pregunta General:
```
Nombre: Bebidas programando
Pregunta: ¿Qué tomas mientras programas?
Categoría: general

Respuestas:
1. Café - 45 pts
2. Agua - 25 pts
3. Energética - 15 pts
4. Té - 8 pts
5. Refresco - 5 pts
6. Cerveza - 2 pts
```

---

## 📱 Capturas de Pantalla

### Banco de Preguntas:
- Lista de todas las preguntas con categorías
- Botones de acción visibles
- Estados activo/inactivo diferenciados

### Controller:
- Pregunta cargada automáticamente
- Respuestas listas para usar
- Controles de ronda

### Tablero:
- Pregunta mostrada arriba
- 6 respuestas en grid 2x3
- Puntajes de equipos
- Multiplicador visible

---

## ✅ Checklist de Funcionalidades

- ✅ Crear pregunta nueva
- ✅ Editar pregunta existente
- ✅ Eliminar pregunta
- ✅ Activar/Desactivar pregunta
- ✅ Cargar pregunta al controller
- ✅ Auto-enviar al tablero
- ✅ Contador de veces usada
- ✅ Categorización
- ✅ Validación de datos
- ✅ Confirmación al eliminar
- ✅ Indicadores visuales de estado
- ✅ Integración completa con el juego

---

## 🎉 ¡Todo Listo!

Ahora puedes:
1. ✅ Gestionar preguntas desde la interfaz web
2. ✅ Cargarlas al controller con un click
3. ✅ Jugar en el tablero
4. ✅ Todo guardado en MySQL

**¡Disfruta el juego!** 🎮
