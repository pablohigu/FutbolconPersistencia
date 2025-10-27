<?php
abstract class GenericDAOTeams {

  //Conexión a BD
  protected $conn = null;
  //Constructor de la clase
  public function __construct() {
    $this->conn = PersistentManager::getInstance()->get_connection();
  }

  // métodos abstractos para CRUD de clases que hereden
  abstract protected function insert($name, $stadium);
  abstract protected function selectAll();
  abstract protected function selectById($id);
  abstract protected function update($id, $name,$stadium);
  abstract protected function delete($id);

}