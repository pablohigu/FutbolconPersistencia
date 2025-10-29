<?php
/**
 * @title: Proyecto de Competición - Página de Equipos
 * @description: Muestra los equipos, permite añadir nuevos y enlaza a sus partidos.
 *
 * @version    1.0
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */
session_start();

if (isset($_SESSION['team_id']) && !empty($_SESSION['team_id']) ) {
    header('Location: team_matches.php?id=' . $_SESSION['team_id']);
    exit();
}
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../persistence/DAO/TeamDAO.php';

$error = ""; // Variable para guardar el mensaje de error
$teamDAO = new TeamDAO();

// Lógica para procesar el formulario de añadir equipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_team'])) {
    $name = trim($_POST['name'] ?? '');
    $stadium = trim($_POST['stadium'] ?? '');
    
    if (!empty($name) && !empty($stadium)) {
        // Primero, comprobamos si el equipo ya existe
        if ($teamDAO->checkExistsByName($name)) {
            $error = "El equipo '" . htmlspecialchars($name) . "' ya está registrado.";
        } else {
            // Si no existe, lo insertamos
            $teamDAO->insert($name, $stadium);
            
            // Redirigimos para limpiar el formulario y evitar reenvíos
            header("Location: teams.php");
            exit();
        }
    }
}

// Obtenemos todos los equipos de la base de datos como un array de arrays asociativos
$teams = $teamDAO->selectAll();

include __DIR__ . '/../templates/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Equipos</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para añadir nuevo equipo -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Añadir Nuevo Equipo</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="teams.php">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-5">
                        <label for="name">Nombre del Equipo</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Ej: Real Madrid" required>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="stadium">Estadio</label>
                        <input type="text" class="form-control" id="stadium" name="stadium" placeholder="Ej: Santiago Bernabéu" required>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" name="add_team" class="btn btn-primary w-100">Añadir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de equipos existentes -->
    <h3>Equipos en la Competición</h3>
    <div class="list-group">
        <?php if (!empty($teams)): ?>
            <?php foreach ($teams as $team): ?>
                <a href="team_matches.php?id=<?= htmlspecialchars($team['id']) ?>" class="list-group-item list-group-item-action">
                    <h5 class="mb-1"><?= htmlspecialchars($team['nombre']) ?></h5>
                    <p class="mb-1 text-muted">Estadio: <?= htmlspecialchars($team['estadio']) ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No hay equipos registrados todavía. ¡Añade el primero!</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>