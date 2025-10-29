<?php
require_once 'GenericDAO.php';

class MatchDAO extends GenericDAO {

    const MATCH_TABLE = 'partido';

    public function selectAll() {
        $query = "SELECT p.*, el.nombre as local_team_name, ev.nombre as visitor_team_name 
                  FROM " . self::MATCH_TABLE . " p
                  JOIN equipo el ON p.id_equipo_local = el.id
                  JOIN equipo ev ON p.id_equipo_visitante = ev.id
                  ORDER BY p.jornada, p.id";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function selectById($id) {
        $query = "SELECT * FROM " . self::MATCH_TABLE . " WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
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

    /**
     * Selecciona todas las jornadas distintas que existen en la BD.
     */
    public function selectAllJornadas() {
        $query = "SELECT DISTINCT jornada FROM " . self::MATCH_TABLE . " ORDER BY jornada ASC";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * Selecciona todos los partidos de una jornada específica.
     */
    public function selectByJornada($jornada) {
        $query = "SELECT 
                    p.jornada, p.resultado,
                    el.nombre as local_team_name, el.estadio as stadium,
                    ev.nombre as visitor_team_name
                  FROM " . self::MATCH_TABLE . " p
                  JOIN equipo el ON p.id_equipo_local = el.id
                  JOIN equipo ev ON p.id_equipo_visitante = ev.id
                  WHERE p.jornada = ?
                  ORDER BY p.id";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $jornada);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    /**
     * Comprueba si dos equipos ya se han enfrentado, sin importar quién fue local o visitante.
     */
    public function checkIfMatchExists($team1_id, $team2_id) {
        $query = "SELECT id FROM " . self::MATCH_TABLE . " WHERE (id_equipo_local = ? AND id_equipo_visitante = ?) OR (id_equipo_local = ? AND id_equipo_visitante = ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiii', $team1_id, $team2_id, $team2_id, $team1_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt) > 0;
    }

    public function insert($jornada, $local_id, $visitor_id, $resultado) {
        $query = "INSERT INTO " . self::MATCH_TABLE . " (jornada, id_equipo_local, id_equipo_visitante, resultado) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiss', $jornada, $local_id, $visitor_id, $resultado);
        return $stmt->execute();
    }
}
?>