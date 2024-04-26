<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});

session_start();

var_dump($_SESSION);
var_dump($_POST);
var_dump($_GET);


if (isset($_POST['verBase'])) {

    $_SESSION['base'] = $_POST['verBase'];
    $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
    //$bd = new gestorBD('172.17.0.2', 'root', 'root', $_POST['verBase']);
    $msj = (string) $bd;
    $html = $bd->consultarTablas($_POST['verBase']);
    $bd->cerrar();
    $ruta = "tablas de " . $_POST['verBase'];
} else {

    if ($_GET['volver'] == 'volver') {
        $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
        //$bd = new gestorBD('172.17.0.2', 'root', 'root', $_POST['verBase']);
        $msj = (string) $bd;
        $html = $bd->consultarTablas($_SESSION['base']);
        $bd->cerrar();
        $ruta = "tablas de " . $_SESSION['base'];
    } else {

        header("Location:index2.php");
    }
}
?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <h2><?= $msj ?></h2>

        <fieldset style="width:40%;margin:0 auto;">



            <legend><?= $ruta ?></legend>

            <form class="" action="listado.php" method="post">
<?= $html ?>

            </form>

        </fieldset>
    </body>
</html>
