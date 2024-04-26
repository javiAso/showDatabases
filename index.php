<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});


if (isset($_POST['submit'])) {


    switch ($_POST['submit']) {
        case 'ver bases de datos':


            $bd = new gestorBD('172.17.0.2', 'root', 'root');
            $msj = (string) $bd;
            $html = $bd->consultarBD();
            $bd->cerrar();
            $ruta = 'bases de datos';

            break;

        case 'insertar':

            var_dump($_POST);

            $bd = new gestorBD('172.17.0.2', 'root', 'root', $_POST['database']);
            $bd->insertarFila($_POST, $_POST['tabla']);
            $msj = (string) $bd;
            $html = $bd->consultarTabla($_POST['tabla'], $_POST['database']);
            $bd->cerrar();
            $ruta = "tabla: " . $_POST['tabla'];


            break;

        default:


            $bd = new gestorBD('172.17.0.2', 'root', 'root', $_POST['submit']);
            $msj = (string) $bd;
            $html = $bd->consultarTablas($_POST['submit']);
            $bd->cerrar();
            $ruta = "tablas de " . $_POST['submit'];


            break;
    }
} else {

    if (isset($_POST['database'])) {


        $bd = new gestorBD('172.17.0.2', 'root', 'root', $_POST['database']);
        $msj = (string) $bd;
        $html = $bd->consultarTabla($_POST['tabla'], $_POST['database']);
        $bd->cerrar();
        $ruta = "tabla: " . $_POST['tabla'];
    } else {
        $msj = "";
        $html = "<input type='submit' name='submit' value='ver bases de datos'>";
        $ruta = "Inicio";
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

            <form class="" action="index.php" method="post">
                <?= $html ?>

            </form>

        </fieldset>
    </body>
</html>
