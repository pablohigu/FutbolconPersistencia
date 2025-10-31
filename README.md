
-----

# Gestor de Competición de Fútbol

Un proyecto de aplicación web PHP para la gestión de equipos y partidos de una liga de fútbol, construido con una arquitectura orientada a objetos y un patrón DAO.
-----

## 1\. Características Principales

  * **Gestión de Equipos:**
      * Listado de todos los equipos y sus estadios.
      * Añadir nuevos equipos con validación de duplicados.
      * Ver el historial de partidos de un equipo específico.
  * **Gestión de Partidos:**
      * Visualización de partidos y resultados filtrados por jornada.
      * Añadir nuevos partidos con resultado (1, X, 2).
      * **Validaciones Avanzadas**:
          * Un equipo no puede jugar contra sí mismo.
          * Un equipo no puede jugar más de un partido por jornada.
          * El sistema comprueba si dos equipos ya se han enfrentado.
  * **Navegación y Sesión:**
      * El sistema recuerda el último equipo consultado y redirige al usuario.
      * Funcionalidad de "Salir" para destruir la sesión.

## 2\. Pila Tecnológica

  * **Backend:** PHP (Programación Orientada a Objetos).
  * **Base de Datos:** MySQL (gestionada con `mysqli`).
  * **Frontend:** HTML5, Bootstrap 4, Font Awesome (para iconos).
  * **Arquitectura:** Patrón de diseño DAO (Data Access Object) para separar la lógica de la base de datos.

## 3\. Estructura del Proyecto

```
/
├── app/               # Vistas y controladores (teams.php, matches.php)
├── persistence/       # Lógica de acceso a datos
│   ├── DAO/           # Clases DAO (TeamDAO.php, MatchDAO.php)
│   ├── conf/          # Gestor de conexión (PersistentManager.php) y credenciales
│   └── sql/           # Script BBDD (basededatos.sql)
├── templates/         # Partes reutilizables (header.php)
├── utils/             # Clases de utilidad (SessionHelper.php)
└── index.php          # Punto de entrada de la aplicación
```

## 4\. Instalación

1.  **Base de Datos:**
      * Crea una base de datos en tu servidor MySQL (p.ej., `competicion_db`).
      * Ejecuta el script `persistence/sql/basededatos.sql` para crear las tablas e insertar datos de ejemplo.
2.  **Configuración:**
      * Edita `persistence/conf/credentials.json` con tus credenciales de acceso a MySQL (host, usuario, contraseña, nombre de la BBDD).
3.  **URL de la Aplicación:**
      * Abre `templates/header.php`.
      * Modifica la variable `$urlApp` para que coincida con la ruta de tu proyecto en el servidor web (ej. `Futbol/ArteanV1/`).
4.  **Servidor:**
      * Sube los archivos a un servidor compatible con PHP (XAMPP, WAMP, etc.).
      * Accede a la URL configurada.

## 5\. Próximos Pasos: Cómo Seguir Desarrollando

El proyecto tiene una base sólida. Aquí se detallan las mejoras lógicas para convertirlo en una aplicación más completa y robusta.

### 1\. Funcionalidad CRUD Completa

Actualmente, el proyecto se centra en "Crear" y "Leer" (Create, Read). Faltarían "Actualizar" y "Eliminar" (Update, Delete).

  * **Editar Partidos:** Añadir un botón "Editar" en `matches.php` para cambiar un resultado (1, X, 2) si se introdujo incorrectamente.
  * **Eliminar Partidos:** Añadir un botón "Eliminar" para borrar un partido.
  * **Editar Equipos:** Permitir cambiar el nombre o el estadio de un equipo.
  * **Eliminar Equipos:** Permitir dar de baja a un equipo. (La BBDD actual ya está preparada para esto con `ON DELETE CASCADE`, lo que borraría sus partidos automáticamente).

### 2\. Generación de una Tabla de Clasificación

Esta es la funcionalidad más importante que falta.

1.  **Modificar la BBDD (Recomendado):**
      * Modifica la tabla `Partido` en `basededatos.sql`.
      * En lugar de `resultado CHAR(1)`, usa `goles_local INT` y `goles_visitante INT`.
      * El resultado (1, X, 2) se puede calcular dinámicamente en PHP.
2.  **Crear Lógica de Puntos:**
      * Crea una nueva clase (ej. `StandingsDAO.php`) que consulte *todos* los partidos.
      * Debe calcular: Puntos (3 por victoria, 1 empate), Partidos Jugados, Victorias, Empates, Derrotas, Goles a Favor, Goles en Contra.
3.  **Crear Vista de Clasificación:**
      * Crea una nueva página `standings.php` que muestre esta tabla ordenada por puntos.
      * Añádela al menú en `templates/header.php`.

### 3\. Autenticación y Roles de Usuario

Actualmente, la aplicación es pública.

1.  **Crear tabla `Usuario`:** Añadir una tabla `Usuario` en la BBDD con `username` y `password` (usando `password_hash()` de PHP para guardar la contraseña de forma segura).
2.  **Crear `login.php`:** Un formulario de inicio de sesión que verifique al usuario y lo guarde en `$_SESSION` (usando `SessionHelper.php`).
3.  **Proteger Páginas:** En la cabecera de `teams.php`, `matches.php`, etc., comprobar si el usuario ha iniciado sesión. Si no, redirigir a `login.php`.
4.  **Roles (Avanzado):** Se podría ampliar la tabla `Usuario` con un campo `rol` ('admin' o 'visitante') para que solo los 'admin' puedan añadir/editar datos.

### 4\. Refactorización a MVC (Modelo-Vista-Controlador)

Los archivos en `/app` mezclan lógica PHP (controlador) y HTML (vista). Para un proyecto más grande, es mejor separarlos.

  * **Controladores:** Archivos PHP puros que reciben `$_POST`, llaman a los DAOs y cargan una vista.
  * **Vistas:** Archivos `.php` que solo contienen HTML y `echo` de variables.
  * **Enrutador (Router):** Un `index.php` avanzado que lee la URL (ej. `/teams`) y decide qué controlador ejecutar, en lugar de solo redirigir.

### 5\. Mejoras de Experiencia de Usuario (UX/UI)

  * **Mensajes "Flash":** El `$error` actual solo se muestra si hay un error. Sería ideal usar la sesión (`$_SESSION`) para mostrar mensajes de éxito *después* de redirigir (ej. "¡Equipo añadido correctamente\!").
  * **AJAX:** Usar JavaScript (Fetch API) para que al añadir un equipo en `teams.php`, la lista de equipos se actualice sin recargar toda la página.
