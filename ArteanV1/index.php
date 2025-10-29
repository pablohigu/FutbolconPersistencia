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
if (isset($_SESSION['team_id']) && !empty($_SESSION['team_id'])) {
    header('Location: app/team_matches.php?id=' . $_SESSION['team_id']);
    exit();
} else {
    // Si el usuario no tiene sesión (o no ha consultado un equipo), la página principal es la de equipos.
    header('Location: app/teams.php');
    exit();
}

// El resto del código HTML de este archivo ya no es necesario,
// ya que el script siempre redirige antes de llegar a él.