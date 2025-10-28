<?php
require_once __DIR__ . '/../conf/PersistentManager.php';

class MatchDAO {

    const MATCH_TABLE = 'partido';
    private $conn = null;

    public function __construct() {
        $this->conn = PersistentManager::getInstance()->get_connection();
    }
    /**
     * Selecciona todos los partidos de un equipo específico, ya sea como local o visitante.
     * Se une con la tabla de equipos para obtener los nombres de los contrincantes.
     */
    public function selectByTeamId($teamId) {
        $query = "SELECT 
                    p.id, 
                    p.jornada,
                    p.resultado,
                    p.id_equipo_local,
                    p.id_equipo_visitante,
                    el.nombre as local_team_name,
                    ev.nombre as visitor_team_name
                  FROM " . self::MATCH_TABLE . " p
                  JOIN equipo el ON p.id_equipo_local = el.id
                  JOIN equipo ev ON p.id_equipo_visitante = ev.id
                  WHERE p.id_equipo_local = ? OR p.id_equipo_visitante = ?
                  ORDER BY p.jornada DESC";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $teamId, $teamId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>