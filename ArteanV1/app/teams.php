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
require_once __DIR__ . '/../persistence/DAO/TeamDAO.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
$teamDAO = new TeamDAO();

// Lógica para añadir un nuevo equipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_team'])) {
    $name = $_POST['name'] ?? '';
    $stadium = $_POST['stadium'] ?? '';

    if (!empty($name) && !empty($stadium)) {
        $teamDAO->insert($name, $stadium);
        // Redirigir para evitar reenvío del formulario
        header("Location: teams.php");
        exit();
    }
}

// Obtener todos los equipos para mostrarlos
$teams = $teamDAO->selectAll();

include __DIR__ . '/../templates/header.php';
?>


<div class="container">
    <h2 class="my-4">Gestión de Equipos</h2>

    <!-- Formulario para añadir nuevo equipo -->
    <div class="card mb-4">
        <div class="card-header">
            Añadir Nuevo Equipo
        </div>
        <div class="card-body">
            <form method="POST" action="teams.php">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="name">Nombre del Equipo</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del Equipo" required>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="stadium">Estadio</label>
                        <input type="text" class="form-control" id="stadium" name="stadium" placeholder="Nombre del Estadio" required>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" name="add_team" class="btn btn-primary w-100">Añadir Equipo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de equipos -->
    <h3>Equipos en la Competición</h3>
    <div class="list-group">
        <?php if (!empty($teams)): ?>
            <?php foreach ($teams as $team): ?>
                <a href="team_matches.php?id=<?= htmlspecialchars($team['id']) ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= htmlspecialchars($team['name']) ?></h5>
                    </div>
                    <p class="mb-1">Estadio: <?= htmlspecialchars($team['stadium']) ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay equipos registrados todavía.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
