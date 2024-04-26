<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});
session_start();

var_dump($_POST);



if (isset($_POST['tabla'])) {


    $_SESSION['tabla'] = $_POST['tabla'];

    if (isset($_POST['volver'])) {
        header("Location:tablas.php?volver=volver");
    }



    if (isset($_POST['insertar'])) {


        $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
        $bd->insertarFila($_POST, $_SESSION['tabla']);
        $msj = (string) $bd;
        $html = $bd->consultarTabla($_SESSION['tabla'], $_SESSION['base']);
        $bd->cerrar();
        $ruta = "tabla: " . $_SESSION['tabla'];
    } else {

        if (isset($_POST['borrar'])) {


            $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
            $bd->borrar($_SESSION['tabla'], $_POST['borrar']);
            $msj = (string) $bd;
            $html = $bd->consultarTabla($_SESSION['tabla'], $_SESSION['base']);
            $bd->cerrar();
            $ruta = "tabla: " . $_SESSION['tabla'];


            var_dump($_POST);
        } else {

            $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
            $msj = (string) $bd;
            $html = $bd->consultarTabla($_SESSION['tabla'], $_SESSION['base']);
            $bd->cerrar();
            $ruta = "tablas de " . $_SESSION['tabla'];
        }
    }
} else {
    if (isset($_POST['submit'])) {
        //echo 'todoguay';
        switch ($_POST['submit']) {
            case 'Volver':

                header("Location:baseDatos.php?submit=volver");

                break;

            case 'atras':


                $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
                $msj = (string) $bd;
                $html = $bd->consultarTabla($_SESSION['tabla'], $_SESSION['base']);
                $bd->cerrar();
                $ruta = "tablas de " . $_SESSION['tabla'];
                break;

            case'modificar':

                echo 'modificar';

                $bd = new gestorBD($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['base']);
                $bd->modificar($_POST, $_SESSION['tabla']);
                $html = $bd->consultarTabla($_SESSION['tabla'], $_SESSION['base']);
                $msj = (string) $bd;
                $bd->cerrar();
                $ruta = "tablas de " . $_SESSION['tabla'];
                break;

            default:

                var_dump($_POST);

                break;
        }
    } else {

        header("Location:index2.php");
        var_dump($_POST);
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
