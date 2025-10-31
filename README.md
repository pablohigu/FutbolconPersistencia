Gestor de Competición de Fútbol (ArteanV1)
Este es un proyecto de aplicación web PHP diseñada para gestionar una competición de fútbol simple. Permite a los usuarios administrar equipos y registrar los resultados de los partidos por jornada.

1. Características Principales
Gestión de Equipos:

Ver un listado de todos los equipos registrados y sus estadios.

Añadir nuevos equipos a la competición (con validación para evitar nombres duplicados).

Acceder al historial de partidos de un equipo específico.

Gestión de Partidos:

Ver los partidos y resultados filtrados por jornada.

Añadir nuevos partidos con su resultado (1, X, 2).

Validaciones avanzadas:

Un equipo no puede jugar contra sí mismo.

Un equipo no puede jugar más de un partido por jornada.

El sistema comprueba si esos dos equipos ya se han enfrentado en una jornada anterior.

Navegación y Sesión:

El sistema recuerda el último equipo consultado por el usuario y lo redirige a la página de ese equipo al volver a entrar.

Incluye una funcionalidad de "Salir" para limpiar la sesión.

2. Pila Tecnológica
Backend: PHP

Base de Datos: MySQL (gestionada con mysqli)

Frontend: HTML5, Bootstrap 4, Font Awesome (para iconos).

Arquitectura: Programación orientada a objetos (POO) con un patrón de diseño DAO (Data Access Object) para separar la lógica de negocio del acceso a datos.

3. Estructura del Proyecto
El proyecto está organizado en las siguientes carpetas principales:

/app: Contiene los archivos PHP que actúan como controladores y vistas (páginas principales de la aplicación como teams.php, matches.php).

/persistence: Contiene toda la lógica de acceso a datos.

/DAO: Clases DAO (TeamDAO.php, MatchDAO.php) que ejecutan las consultas SQL.

/conf: Gestor de conexión a la BBDD (PersistentManager.php) y archivo de credenciales (credentials.json).

/sql: Script de creación de la base de datos (basededatos.sql).

/templates: Partes reutilizables de la interfaz, como la cabecera y el menú de navegación (header.php).

/utils: Clases de utilidad, como el SessionHelper.php para gestionar las sesiones de usuario.

index.php: Punto de entrada de la aplicación que redirige al usuario según su estado de sesión.

4. Instalación
Base de Datos:

Crea una base de datos en tu servidor MySQL (por defecto, el nombre esperado es competicion_db).

Ejecuta el script persistence/sql/basededatos.sql para crear las tablas Equipo y Partido e insertar datos de ejemplo.

Configuración:

Renombra o copia persistence/conf/credentials.json.example a persistence/conf/credentials.json (si no existe ya).

Edita persistence/conf/credentials.json con tus credenciales de acceso a la base de datos MySQL (host, usuario, contraseña y nombre de la base de datos).

URL de la Aplicación:

Abre el archivo templates/header.php.

Modifica la variable $urlApp para que coincida con la ruta de tu proyecto en el servidor web (ej. Futbol/ArteanV1/).

Servidor:

Sube todos los archivos a un servidor web compatible con PHP (como XAMPP, WAMP o un servidor en producción).

Accede a la URL configurada en tu navegador.

5. Cómo Seguir Desarrollando el Proyecto (Mejoras Futuras)
El proyecto actual es una base excelente. Aquí hay varias líneas de desarrollo para convertirlo en una aplicación más completa y robusta:

1. Funcionalidad CRUD Completa
Actualmente, el proyecto implementa "Crear" (añadir equipos/partidos) y "Leer" (ver equipos/partidos). Faltarían las operaciones "Actualizar" y "Eliminar".

Editar y Eliminar Partidos:

Añadir un botón "Editar" en la lista de partidos (matches.php) para poder cambiar el resultado (1, X, 2) si se introdujo incorrectamente.

Añadir un botón "Eliminar" para borrar un partido creado por error.

Editar y Eliminar Equipos:

Permitir editar el nombre o el estadio de un equipo existente.

Permitir eliminar un equipo. (Importante: deberás decidir qué ocurre con los partidos que ya ha jugado. La configuración ON DELETE CASCADE de la BBDD actual los borraría automáticamente).

2. Generación de una Tabla de Clasificación
Esta es la funcionalidad más importante que le falta a un gestor de competiciones.

Cálculo de Puntos: Crear una nueva clase (ej. StatsDAO o StandingsManager) que calcule la clasificación.

Debe recorrer todos los partidos jugados.

Asignar puntos (ej. 3 por victoria, 1 por empate, 0 por derrota).

Contabilizar partidos jugados, ganados, empatados, perdidos.

Página de Clasificación: Crear una nueva página standings.php que muestre esta tabla ordenada por puntos.

Mejora de la BBDD (Opcional): Para una clasificación más completa (goles a favor, en contra), necesitarías modificar la tabla Partido en basededatos.sql para no guardar solo '1', 'X', '2', sino goles_local y goles_visitante (tipo INT). El resultado se calcularía dinámicamente.

3. Autenticación y Roles de Usuario
Actualmente, cualquier persona que acceda a la web puede añadir y ver datos.

Crear tabla Usuario: Añadir una tabla Usuario en la BBDD con username, password (¡guardada con password_hash(), nunca en texto plano!).

Crear login.php: Un formulario de inicio de sesión.

Proteger Páginas: Modificar header.php o crear un script de "autenticación" para comprobar si $_SESSION['user'] existe en cada página de gestión. Si no, redirigir al login.php.

Roles (Avanzado): Se podría crear un rol de "Administrador" (puede añadir partidos/equipos) y un rol de "Visitante" (solo puede ver la clasificación y los resultados).

4. Refactorización a MVC (Modelo-Vista-Controlador)
Los archivos en /app mezclan lógica PHP (controlador) con HTML (vista). Esto funciona, pero es difícil de mantener a medida que el proyecto crece.

Controladores: Archivos PHP puros que gestionan $_GET/$_POST, llaman a los DAOs y deciden qué vista mostrar.

Vistas: Archivos (ej. views/teams_view.php) que solo contienen HTML y echo de variables que el controlador les ha pasado.

Modelos: Los DAOs y las clases de lógica de negocio ya existentes.

Enrutador (Router): Un index.php más avanzado que, en lugar de solo redirigir, analice la URL y decida qué "controlador" debe ejecutarse.

5. Mejoras en la Experiencia de Usuario (UX/UI)
Mensajes "Flash": El mensaje de error actual ($error) es simple. Una mejora sería usar "mensajes flash" (mensajes guardados en la sesión que se muestran una sola vez después de redirigir) para confirmar acciones como "¡Equipo añadido correctamente!".

AJAX: Usar JavaScript (y quizás jQuery o Fetch API) para que la página de "Partidos" pueda filtrar por jornada o añadir un partido sin tener que recargar la página entera.
