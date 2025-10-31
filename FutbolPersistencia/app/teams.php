<?php
/**
 * @title: Proyecto de Competición - Página de Equipos
 * @description: Muestra los equipos, permite añadir nuevos y enlaza a sus partidos.
 *
 * @version    1.0
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/DAO/TeamDAO.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

SessionHelper::startSessionIfNotStarted();
if (isset($_SESSION['team_id']) && !empty($_SESSION['team_id']) ) {
    header('Location: team_matches.php?id=' . $_SESSION['team_id']);
    exit();
}
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
            header("Location: /" . $urlApp . "app/teams.php");
            exit();
        }
    }
}

// Obtenemos todos los equipos de la base de datos como un array de arrays asociativos
$teams = $teamDAO->selectAll();
?>

<div class="container mt-4">
    <h2 class="mb-4 border-bottom pb-2"><i class="fas fa-shield-alt mr-2"></i>Gestión de Equipos</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para añadir nuevo equipo -->
    <div class="card mb-4">
        <div class="card-header">
            Añadir Nuevo Equipo
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
    <h3 class="mb-3">Equipos en la Competición</h3>
    <div class="row">
        <?php if (!empty($teams)): ?>
            <?php foreach ($teams as $team): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><i class="fas fa-users mr-2 text-primary"></i><?= htmlspecialchars($team['nombre']) ?></h5>
                            <p class="card-text text-muted flex-grow-1"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($team['estadio']) ?></p>
                            <a href="/<?= $urlApp ?>app/team_matches.php?id=<?= htmlspecialchars($team['id']) ?>" class="btn btn-outline-primary mt-auto">Ver Partidos</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No hay equipos registrados todavía. ¡Añade el primero!</p>
        <?php endif; ?>
    </div>
    </main>
</div>    
</body>
</html>