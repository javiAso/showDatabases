<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});

session_start();
var_dump($_GET);
var_dump($_POST);

if (isset($_POST['submit'])) {

    $origen = $_POST['submit'];
} else {

    if (isset($_GET['submit'])) {
        $origen = $_GET['submit'];
    } else {

        header("Location:index2.php");
        exit();
    }
}


switch ($origen) {


    case 'conectar':

        $_SESSION['host'] = $_POST['host'];
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['pass'] = $_POST['pass'];



        $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
        $msj = (string) $bd;
        $html = $bd->consultarBD();
        $bd->cerrar();
        $ruta = 'bases de datos';





        var_dump($_SESSION);



        break;

    case 'volver':


        $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
        $msj = (string) $bd;
        $html = $bd->consultarBD();
        $bd->cerrar();
        $ruta = 'bases de datos';


        break;
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

            <form class="" action="tablas.php" method="post">
                <?= $html ?>

            </form>

        </fieldset>
    </body>
</html>
