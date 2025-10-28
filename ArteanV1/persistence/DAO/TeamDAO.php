<?php
require 'GenericDAOTeams.php';
class TeamDAO extends GenericDAOTeams {

    const TEAM_TABLE = 'equipo';
    public function selectAll() {
        $query = "SELECT id, nombre, estadio FROM " . self::TEAM_TABLE;
        $result = mysqli_query($this->conn, $query);
        $teams = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $teams;
    }

    public function selectById($id) {
        $query = "SELECT id, nombre, estadio FROM " . self::TEAM_TABLE . " WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function insert($name, $stadium) {
        $query = "INSERT INTO " . self::TEAM_TABLE . " (nombre, estadio) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $name, $stadium);
        return $stmt->execute();
    }

    public function checkExistsByName($name) {
        $query = "SELECT id FROM " . self::TEAM_TABLE . " WHERE nombre = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        return $count > 0;
    }

    public function delete($id) {
        $query = "DELETE FROM " . self::TEAM_TABLE . " WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return $stmt->execute();
    }
    public function update($id, $name, $stadium) {
        $query = "UPDATE " . self::TEAM_TABLE . " SET nombre = ?, estadio = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $stadium, $id);
        return $stmt->execute();
    }
}
?>