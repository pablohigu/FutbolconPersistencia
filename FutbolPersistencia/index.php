<?php
/**
 * @title: Proyecto de Competición - Página principal
 * @description:  Punto de entrada de la aplicación. Redirige o muestra bienvenida.
 *
 * @version    1.0
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */
require_once __DIR__ . '/utils/SessionHelper.php';

SessionHelper::startSessionIfNotStarted();

// Definimos la URL base de la aplicación.
$urlApp = "Futbol/FutbolPersistencia/";

// Si el usuario ha consultado un equipo anteriormente, se le redirige a la página de partidos de ese equipo.
if (isset($_SESSION['team_id']) && !empty($_SESSION['team_id'])) {
    header('Location: /' . $urlApp . 'app/team_matches.php?id=' . $_SESSION['team_id']);
    exit();
} else {
    // Si el usuario no tiene sesión (o no ha consultado un equipo), la página principal es la de equipos.
    header('Location: /' . $urlApp . 'app/teams.php');
    exit();
}