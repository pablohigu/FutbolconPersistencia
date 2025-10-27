<?php
/**
 * @title: Proyecto de Competición - Cabecera y barra de navegación.
 * @description:  Muestra el menú principal de la aplicación.
 *
 * @version    1.0
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */

  // NOTA: El antiguo SessionHelper para login/logout ya no es necesario aquí.
  // La lógica de sesión se manejará en las páginas que lo requieran.
  // La variable $loggedin y $user han sido eliminadas.
?>
<head>
    <meta charset="utf-8">
    <title>Gestor de Competición</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>


<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler navbar-toggler-right" type="button"
            data-toggle="collapse" data-target="#navbarToggler"
            aria-controls="navbarToggler" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/Futbol/ArteanV1/index.php">Competición</a>

    <div class="collapse navbar-collapse" id="navbarToggler">
        <ul class="navbar-nav mr-auto mt-2 mt-md-0">
            <li class="nav-item">
                <a class="nav-link" href="/Futbol/ArteanV1/app/teams.php">Equipos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/Futbol/ArteanV1/app/matches.php">Partidos</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Bootstrap y dependencias JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
