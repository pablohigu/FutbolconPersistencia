<?php
/**
 * @title: Proyecto de Competición - Página de Partidos
 * @description: Muestra los partidos por jornada y permite añadir nuevos.
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
$error = "";
$teamDAO = new TeamDAO();
$matchDAO = new MatchDAO();

// Lógica para AÑADIR un nuevo partido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_match'])) {
    $jornada = $_POST['jornada'];
    $local_id = $_POST['team_local_id'];
    $visitor_id = $_POST['team_visitor_id'];
    $resultado = $_POST['resultado'];

    // Validaciones
    if ($local_id == $visitor_id) {
        $error = "Un equipo no puede jugar contra sí mismo.";
    } elseif ($matchDAO->checkIfMatchExists($local_id, $visitor_id)) {
        $error = "Estos dos equipos ya se han enfrentado en una jornada anterior.";
    } elseif ($matchDAO->checkIfTeamPlaysInJornada($local_id, $jornada)) {
        $error = "El equipo local ya tiene un partido en esta jornada.";
    } elseif ($matchDAO->checkIfTeamPlaysInJornada($visitor_id, $jornada)) {
        $error = "El equipo visitante ya tiene un partido en esta jornada.";
    } else {
        $matchDAO->insert($jornada, $local_id, $visitor_id, $resultado);
        header("Location: /" . $urlApp . "app/matches.php?jornada=" . $jornada); // Redirigir a la jornada recién actualizada
        exit();
    }
}

// Lógica para MOSTRAR partidos
$all_teams = $teamDAO->selectAll();
$all_jornadas = $matchDAO->selectAllJornadas();

$selected_jornada = $_GET['jornada'] ?? ($all_jornadas[0]['jornada'] ?? null);
$matches_of_jornada = [];
if ($selected_jornada) {
    $matches_of_jornada = $matchDAO->selectByJornada($selected_jornada);
}

?>

<div class="container mt-4">
    <h2 class="mb-4 border-bottom pb-2"><i class="fas fa-calendar-alt mr-2"></i>Gestión de Partidos</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endif; ?>

    <!-- Formulario para AÑADIR nuevo partido -->
    <div class="card mb-5">
        <div class="card-header">
            Añadir Nuevo Partido
        </div>
        <div class="card-body">
            <form method="POST" action="matches.php">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-2">
                        <label for="jornada">Jornada</label>
                        <input type="number" class="form-control" id="jornada" name="jornada" min="1" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="team_local_id">Equipo Local</label>
                        <select id="team_local_id" name="team_local_id" class="form-control" required>
                            <option value="" selected disabled>Seleccionar...</option>
                            <?php foreach ($all_teams as $team): ?>
                                <option value="<?= $team['id'] ?>"><?= htmlspecialchars($team['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="team_visitor_id">Equipo Visitante</label>
                        <select id="team_visitor_id" name="team_visitor_id" class="form-control" required>
                            <option value="" selected disabled>Seleccionar...</option>
                            <?php foreach ($all_teams as $team): ?>
                                <option value="<?= $team['id'] ?>"><?= htmlspecialchars($team['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="resultado">Resultado (1, X, 2)</label>
                        <select id="resultado" name="resultado" class="form-control" required>
                            <option value="1">1</option>
                            <option value="X">X</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" name="add_match" class="btn btn-primary w-100">Añadir Partido</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Filtro y visualización de partidos por JORNADA -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Resultados de la Jornada</h3>
        <form method="GET" action="matches.php" class="form-inline">
            <label for="jornada_filter" class="mr-2">Seleccionar Jornada:</label>
            <select name="jornada" id="jornada_filter" class="form-control" onchange="this.form.submit()">
                <?php if (empty($all_jornadas)): ?>
                    <option disabled selected>No hay jornadas</option>
                <?php else: ?>
                    <?php foreach ($all_jornadas as $j): ?>
                        <option value="<?= $j['jornada'] ?>" <?= $j['jornada'] == $selected_jornada ? 'selected' : '' ?>>
                            Jornada <?= $j['jornada'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </form>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <?php if (!empty($matches_of_jornada)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($matches_of_jornada as $match): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-weight-bold"><?= htmlspecialchars($match['local_team_name']) ?></span>
                                <span class="text-muted mx-2">vs</span>
                                <span class="font-weight-bold"><?= htmlspecialchars($match['visitor_team_name']) ?></span>
                                <br>
                                <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($match['stadium']) ?></small>
                            </div>
                            <span class="badge badge-dark p-2" style="font-size: 1.1rem; min-width: 45px;"><?= htmlspecialchars($match['resultado']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-muted p-4">No hay partidos registrados para esta jornada.</p>
            <?php endif; ?>
        </div>
    </div>
    </main>
</div>    
</body>
</html>