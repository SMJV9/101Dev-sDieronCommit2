# 🎮 100 Programadores Dijieron

<div align="center">

**Un juego interactivo estilo "Preguntados" diseñado especialmente para desarrolladores**

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![Made with Laravel](https://img.shields.io/badge/Made%20with-Laravel-red.svg)](https://laravel.com/)

</div>

---

## 🎯 ¿Qué es este proyecto?

**100 Programadores Dijieron** es un juego interactivo inspirado en el famoso programa de televisión "100 Mexicanos Dijieron". Está diseñado específicamente para desarrolladores y equipos de tecnología, con preguntas relacionadas con programación, desarrollo web, y la cultura geek.

### ✨ Características Principales

🎮 **Juego en Tiempo Real** - Experiencia multijugador con tablero dinámico  
📋 **Sistema CRUD Completo** - Gestiona preguntas y respuestas fácilmente  
🏆 **Sistema de Puntuación** - Equipos competitivos con multiplicadores  
🎨 **Interfaz Moderna** - Diseño responsivo y animaciones atractivas  
⚡ **Tiempo Real** - Sincronización automática entre controller y tablero  
📊 **Estadísticas** - Tracking de preguntas más utilizadas  

---

## 🚀 Inicio Rápido

### Prerrequisitos

```bash
PHP >= 8.2
Composer
Node.js & NPM
MySQL 8.0+
```

### 1. Clonar el repositorio

```bash
git clone https://github.com/SMJV9/alexpelon.git
cd alexpelon
```

### 2. Instalar dependencias

```bash
# Dependencias PHP
composer install

# Dependencias JavaScript
npm install
```

### 3. Configurar entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=devdijieron
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 4. Preparar base de datos

```bash
# Ejecutar migraciones
php artisan migrate

# Cargar datos de ejemplo
php artisan db:seed --class=QuestionSeeder
```

### 5. Iniciar el servidor

```bash
# Servidor Laravel
php artisan serve

# Compilar assets (en otra terminal)
npm run dev
```

**🎉 ¡Listo!** Visita `http://localhost:8000`

---

## 🎮 Cómo Jugar

### 🎯 Flujo del Juego

1. **Crear/Gestionar Preguntas** → `/questions`
2. **Controlar el Juego** → `/controller` 
3. **Mostrar Tablero** → `/tablero`

### 📱 Pantallas del Juego

| Ruta | Descripción | Función |
|------|-------------|---------|
| `/` | Página de inicio | Bienvenida y navegación |
| `/questions` | **Banco de preguntas** | CRUD completo de preguntas |
| `/controller` | **Panel de control** | Controlar el juego en vivo |
| `/tablero` | **Tablero principal** | Display para la audiencia |

### 🎲 Ejemplo de Partida

```
1. 💻 Admin crea pregunta: "¿Qué lenguaje usan más?"
   Respuestas: JavaScript (40pts), Python (30pts), Java (20pts)...

2. 🎮 Controller carga la pregunta al tablero

3. 👥 Equipos compiten por revelar respuestas

4. 🏆 Puntos se acumulan automáticamente

5. 🎯 El equipo con más puntos gana la ronda
```

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 12** - Framework PHP moderno
- **MySQL** - Base de datos relacional
- **Eloquent ORM** - Manejo de datos elegante

### Frontend  
- **Blade Templates** - Sistema de plantillas de Laravel
- **Vite** - Build tool moderno
- **CSS3** - Estilos personalizados con animaciones
- **JavaScript ES6+** - Interactividad dinámica

### Desarrollo
- **Composer** - Gestión de dependencias PHP
- **NPM** - Gestión de dependencias JavaScript
- **Laravel Pint** - Code styling
- **PHPUnit** - Testing

---

## 📊 Estructura del Proyecto

```
📦 100-programadores-dijieron/
├── 🎮 app/
│   ├── Http/Controllers/     # Lógica del juego
│   └── Models/              # Game, Question, Answer, Round
├── 🗄️ database/
│   ├── migrations/          # Estructura de BD
│   └── seeders/            # Datos de ejemplo
├── 🎨 resources/
│   ├── views/              # Pantallas del juego
│   ├── css/               # Estilos (board, controller, questions)
│   └── js/                # JavaScript interactivo
├── 🛣️ routes/
│   └── web.php            # Rutas de la aplicación
└── 📚 Documentación/
    ├── GUIA_RAPIDA.md     # Tutorial de uso
    ├── DATABASE.md        # Esquema de BD
    └── CRUD_PREGUNTAS.md  # Manual CRUD
```

---

## 🎯 Características Destacadas

### 💡 Sistema CRUD Inteligente
- ✅ Crear preguntas con múltiples respuestas
- ✏️ Editar preguntas existentes
- 🔒 Activar/Desactivar preguntas
- 📊 Estadísticas de uso
- 🏷️ Categorización automática

### 🎮 Controller Avanzado
- 🚀 Carga automática de preguntas
- ⏱️ Sistema de cuenta regresiva
- 🏆 Gestión de equipos y puntuación
- ✨ Multiplicadores de ronda
- ❌ Sistema de 3 strikes

### 🖥️ Tablero Dinámico
- 📺 Visualización en tiempo real
- 🎨 Animaciones fluidas
- 📱 Diseño responsivo
- 🎯 Indicadores visuales
- 🔄 Sincronización automática

---

## 📝 Documentación Adicional

| Documento | Descripción |
|-----------|-------------|
| [📖 Guía Rápida](GUIA_RAPIDA.md) | Tutorial paso a paso |
| [🗄️ Base de Datos](DATABASE.md) | Esquema y relaciones |
| [📋 CRUD Manual](CRUD_PREGUNTAS.md) | Sistema de gestión |
| [🔄 Migraciones](MIGRACIONES_COMPLETADAS.md) | Historial de cambios |

---

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! 

### 🚀 Cómo contribuir:

1. **Fork** el proyecto
2. **Crea** una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. **Abre** un Pull Request

### 💡 Ideas para contribuir:
- 🎨 Nuevos temas visuales
- 📱 Mejoras mobile-first
- 🎮 Nuevos tipos de juego
- 🔊 Efectos de sonido
- 📊 Dashboard de estadísticas
- 🌐 Modo multijugador online

---

## 🐛 Reportar Bugs

¿Encontraste un bug? ¡Ayúdanos a mejorarlo!

1. **Verifica** que no esté ya reportado en [Issues](../../issues)
2. **Crea** un nuevo issue con:
   - 📝 Descripción clara del problema
   - 🔄 Pasos para reproducirlo
   - 📱 Información del entorno (OS, PHP, navegador)
   - 📸 Screenshots si es posible

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👨‍💻 Autor

**[SMJV9](https://github.com/SMJV9)**

📧 Email: vasquez.jcesar@gmail.com
🐱 GitHub: [@SMJV9](https://github.com/SMJV9)  
🌐 LinkedIn: [Julio Vasquez](https://www.linkedin.com/in/vasquezajc)  

---

## 🎉 ¡Disfruta el Juego!

<div align="center">

**¿Te gustó el proyecto? ¡Dale una ⭐ en GitHub!**

*Hecho con ❤️ para la comunidad de desarrolladores*

---

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

</div>
