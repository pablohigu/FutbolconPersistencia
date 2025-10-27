<?php
/**
 * @title: Proyecto de Competición - Página principal
 * @description:  Punto de entrada de la aplicación. Redirige o muestra bienvenida.
 *
 * @version    1.0
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */
session_start();

// Si el usuario ha consultado un equipo anteriormente, se le redirige a la página de partidos de ese equipo.
if (isset($_SESSION['team_id'])) {
    // header('Location: app/team_matches.php?id=' . $_SESSION['team_id']);
    exit(); // Es importante terminar el script después de una redirección.
}

include 'templates/header.php';
?>

<div class="container-fluid py-5 my-5 bg-light">
  <div id="bienvenida" class="container">
    <h1 class='display-3'>Bienvenid@ al Gestor de Competiciones</h1>
    <p class='display-6'>Utiliza el menú superior para navegar entre Equipos y Partidos.</p>
  </div>
</div>
<div id="bienvenida" class="img-fluid" alt="Responsive image">
  <div class="row">
    <div class="offset-md-3 col-md-6">
      <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Instituto_Cuatrovientos_-_Cuatrovientos_Institutua.png/640px-Instituto_Cuatrovientos_-_Cuatrovientos_Institutua.png"  alt="" title=""/>
      <p class="lead">Desde Cuatrovientos queremos dar la bienvenida a todo el alumnado que por primera vez se acercan al instituto y a aquellos que continúan con sus programas formativos. </p>
    </div>
  </div>
</div>
<footer class="footer">
  <div class="container">
    <span class="text-muted">Desarrollo Web - 2º DAM.</span>
  </div>
</footer>
</body>

</html>