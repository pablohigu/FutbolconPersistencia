<?php
/**
 * @title: Proyecto de Competición - Cerrar Sesión
 * @description: Destruye la sesión del usuario y redirige al inicio.
 */
session_start();
session_destroy();
header('Location: /Futbol/ArteanV1/index.php');
exit();