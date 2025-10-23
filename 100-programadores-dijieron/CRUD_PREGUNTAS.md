# ✅ Sistema CRUD de Preguntas Implementado

## 🎯 Lo que se implementó:

### 1. API REST Completa
Se creó un controlador `QuestionController` con las siguientes funcionalidades:

#### Endpoints disponibles:
- **GET** `/api/questions` - Listar todas las preguntas
- **POST** `/api/questions` - Crear nueva pregunta
- **GET** `/api/questions/{id}` - Ver pregunta específica
- **PUT** `/api/questions/{id}` - Actualizar pregunta
- **DELETE** `/api/questions/{id}` - Eliminar pregunta
- **POST** `/api/questions/{id}/toggle-active` - Activar/Desactivar pregunta
- **GET** `/api/questions/{id}/load` - Cargar pregunta al controller

### 2. Funcionalidades del CRUD

#### ✅ **Crear Pregunta**
- Validación de datos (nombre único, texto requerido, mínimo 1 respuesta)
- Creación automática de respuestas asociadas
- Categorización (general, dev, tech, etc.)
- Estado activo por defecto

#### ✅ **Editar Pregunta**
- Cargar datos existentes en el formulario
- Actualizar nombre, texto y categoría
- Modificar respuestas (agregar, editar, eliminar)
- Validación de nombre único

#### ✅ **Eliminar Pregunta**
- Confirmación antes de eliminar
- Eliminación en cascada de respuestas automática
- Mensaje de confirmación

#### ✅ **Activar/Desactivar**
- Toggle de estado activo/inactivo
- Indicadores visuales de estado
- Solo preguntas activas aparecen en el juego

#### ✅ **Cargar al Controller**
- Botón para cargar pregunta directamente al controller
- Incrementa contador de veces usada
- Envío automático al tablero
- Formatea respuestas con estructura correcta

### 3. Vista del Banco de Preguntas Mejorada

#### Características nuevas:
- ✅ Conectada a la base de datos (ya no usa localStorage)
- ✅ Campo de categoría en el formulario
- ✅ Botón "Editar" para modificar preguntas
- ✅ Botón "Cancelar" al editar
- ✅ Indicadores de estado (Activa/Inactiva)
- ✅ Badge de categoría
- ✅ Contador de veces usada
- ✅ Estilos diferenciados para preguntas inactivas
- ✅ Token CSRF para seguridad

### 4. Integración con el Controller

#### Flujo de trabajo:
1. **Banco de Preguntas** → Click en "Cargar al Controller"
2. Pregunta se carga en `localStorage` temporalmente
3. **Controller** detecta la pregunta automáticamente
4. Carga las respuestas en el formulario
5. Envía automáticamente al tablero
6. Incrementa el contador de uso en la BD

---

## 🚀 Cómo Usar

### Paso 1: Acceder al Banco de Preguntas
```
http://localhost:8000/questions
```

### Paso 2: Crear una Nueva Pregunta
1. Llenar el formulario:
   - **Nombre**: Identificador único (ej: "Lenguajes JS")
   - **Pregunta**: Texto completo (ej: "¿Qué framework de JavaScript usas?")
   - **Categoría**: Opcional (general, dev, tech, etc.)
2. Click en "+ Agregar Respuesta" para cada respuesta
3. Llenar texto y puntos de cada respuesta
4. Click en "💾 Guardar Pregunta"

### Paso 3: Editar una Pregunta Existente
1. Click en "✏️ Editar" en la pregunta
2. Se carga automáticamente en el formulario
3. Modificar lo necesario
4. Click en "💾 Actualizar Pregunta"
5. O "✖ Cancelar" para abortar

### Paso 4: Cargar al Controller
1. Opción A (desde el banco): Click en "📋 Cargar al Controller" en la tarjeta
2. Opción B (desde el controller): Usa el dropdown "Elegir desde banco" y selecciona la pregunta
3. Se carga automáticamente en el Controller
4. Se envía al tablero al instante
5. Incrementa el contador de uso en la BD

### Paso 5: Gestionar Estado
- **🔒 Desactivar**: Oculta la pregunta (no se puede usar)
- **✅ Activar**: Vuelve a habilitar la pregunta

### Paso 6: Eliminar
1. Click en "🗑️ Eliminar"
2. Confirmar en el diálogo
3. Se elimina de la base de datos

---

## 📊 Ejemplo de Datos

### Pregunta en la Base de Datos:
```json
{
  "id": 1,
  "name": "Lenguajes de programación",
  "question_text": "¿Cuál es el primer lenguaje que aprenden los programadores?",
  "category": "dev",
  "is_active": true,
  "times_used": 5,
  "answers": [
    {
      "id": 1,
      "answer_text": "Python",
      "points": 40,
      "order": 1
    },
    {
      "id": 2,
      "answer_text": "JavaScript",
      "points": 28,
      "order": 2
    }
  ]
}
```

### Al cargar al Controller:
```json
{
  "question": "¿Cuál es el primer lenguaje que aprenden los programadores?",
  "answers": [
    {
      "text": "Python",
      "count": 40,
      "revealed": false,
      "correct": false
    },
    {
      "text": "JavaScript",
      "count": 28,
      "revealed": false,
      "correct": false
    }
  ]
}
```

---

## 🔧 Archivos Modificados/Creados

### Nuevos:
- ✅ `app/Http/Controllers/QuestionController.php` - Controlador del CRUD
- ✅ `CRUD_PREGUNTAS.md` - Esta documentación

### Modificados:
- ✅ `routes/web.php` - Agregadas rutas API
- ✅ `resources/views/questions.blade.php` - Conectado a API
- ✅ `resources/views/controller.blade.php` - Auto-carga de preguntas

---

## 🎨 Indicadores Visuales

### Estados de Pregunta:
- ✅ **Verde con ✓ Activa** - Pregunta disponible para usar
- ❌ **Rojo con ✗ Inactiva** - Pregunta deshabilitada (opacidad 60%)

### Categorías:
- Badge con color cyan para categoría
- Muestra "general", "dev", etc.

### Contador de Uso:
- Muestra cuántas veces se ha usado la pregunta
- Se incrementa automáticamente al cargar

---

## 🔐 Seguridad

### Validaciones Implementadas:
- ✅ Token CSRF en todos los formularios
- ✅ Validación de datos en el servidor
- ✅ Nombre único de pregunta
- ✅ Mínimo 1 respuesta requerida
- ✅ Puntos deben ser números positivos
- ✅ Confirmación antes de eliminar

---

## 🧪 Pruebas

### Probar la API manualmente:
```bash
# Listar todas las preguntas
curl http://localhost:8000/api/questions

# Ver pregunta específica
curl http://localhost:8000/api/questions/1

# Crear pregunta (requiere CSRF token desde el navegador)
# Usar el formulario en /questions
```

### Verificar en la base de datos:
```sql
USE devdijieron;

-- Ver todas las preguntas
SELECT * FROM questions;

-- Ver respuestas de una pregunta
SELECT q.name, a.answer_text, a.points 
FROM questions q 
JOIN answers a ON q.id = a.question_id 
WHERE q.id = 1 
ORDER BY a.order;

-- Ver preguntas más usadas
SELECT name, times_used FROM questions ORDER BY times_used DESC;
```

---

## 🎯 Próximos Pasos Sugeridos

1. ✅ Implementar búsqueda/filtros en el banco de preguntas
2. ✅ Paginación para muchas preguntas
3. ✅ Duplicar preguntas existentes
4. ✅ Importar/Exportar preguntas en JSON
5. ✅ Ordenar respuestas por drag & drop
6. ✅ Historial de cambios en preguntas
7. ✅ Previsualización antes de cargar

---

## ✨ ¡Listo para Usar!

El sistema completo está funcionando. Puedes:
1. Crear preguntas desde `/questions`
2. Editarlas cuando quieras
3. Cargarlas al controller con un click
4. Jugar en el tablero

**Todo se guarda en la base de datos MySQL** 🎉
