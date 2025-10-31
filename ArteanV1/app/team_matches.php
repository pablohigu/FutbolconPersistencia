<?php
/**
 * @title: Proyecto de Competición - Partidos de un Equipo
 * @description: Muestra los partidos y resultados de un equipo específico.
 *
 * @version    1.0
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/DAO/TeamDAO.php';
require_once __DIR__ . '/../persistence/DAO/MatchDAO.php';
require_once __DIR__ . '/../utils/SessionHelper.php';

SessionHelper::startSessionIfNotStarted();
// 1. Obtener el ID del equipo de la URL
$team_id = $_GET['id'] ?? null;

if (!$team_id || !is_numeric($team_id)) {
    // Si no hay ID o no es válido, redirigir a la página de equipos
    header('Location: /' . $urlApp . 'app/teams.php');
    exit();
}

// 2. Crear instancias de los DAOs
$teamDAO = new TeamDAO();
$matchDAO = new MatchDAO();

// 3. Obtener la información del equipo y sus partidos
$team = $teamDAO->selectById($team_id);
$matches = $matchDAO->selectById($team_id);
// Si el equipo no existe, redirigir
if (!$team) {
    header('Location: /' . $urlApp . 'app/teams.php');
    exit();
}
// Guardar en sesión el último equipo consultado
$_SESSION['team_id'] = $team_id;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-users text-primary mr-2"></i><?= htmlspecialchars($team['nombre']) ?></h2>
            <p class="lead text-muted mb-0"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($team['estadio']) ?></p>
        </div>
        <a href="/<?= $urlApp ?>app/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt mr-2"></i>Salir</a>
    </div>

    <div class="card">
        <div class="card-header">
            Historial de Partidos
        </div>
        <div class="card-body">
            <?php if (!empty($matches)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($matches as $match): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-weight-bold <?= $match['id_equipo_local'] == $team_id ? 'text-primary' : '' ?>"><?= htmlspecialchars($match['local_team_name']) ?></span>
                                <span class="text-muted mx-2">vs</span>
                                <span class="font-weight-bold <?= $match['id_equipo_visitante'] == $team_id ? 'text-primary' : '' ?>"><?= htmlspecialchars($match['visitor_team_name']) ?></span>
                            </div>
                            <div class="text-center">
                                <span class="badge badge-dark p-2" style="font-size: 1rem; min-width: 45px;"><?= htmlspecialchars($match['resultado'] ?? 'N/J') ?></span>
                                <small class="d-block text-muted mt-1">Jornada <?= htmlspecialchars($match['jornada']) ?></small>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-muted">Este equipo todavía no ha jugado ningún partido.</p>
            <?php endif; ?>
        </div>
    </div>
    </main>
</div>    
</body>
</html>