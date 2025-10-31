<?php
/**
 * @title: Proyecto de Competición - Cerrar Sesión
 * @description: Destruye la sesión del usuario y redirige al inicio.
 */
require_once __DIR__ . '/../utils/SessionHelper.php';
require_once __DIR__ . '/../templates/header.php'; // Para obtener $urlApp
SessionHelper::destroySession();
header('Location: /' . $urlApp . 'index.php');
exit();