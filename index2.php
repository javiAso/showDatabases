<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>




        <fieldset>

            <legend>Conexion</legend>
            <form class="" action="baseDatos.php" method="post">
                Host<input type="text" name="host" value="">
                Usuario<input type="text" name="user" value="">
                Pass<input type="text" name="pass" value="">
                <input type="submit" name="submit" value="conectar">

            </form>
        </fieldset>



    </body>
</html>
