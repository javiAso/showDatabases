<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function($clase) {
    require "$clase.php";
});
var_dump($_POST);

$html = "";

if (isset($_POST)) {

    foreach ($_POST as $key => $value) {
        if ($value == 'modificar')
            $pKey = $key;
        else
            continue;
    }

    $arrayValues = unserialize($_POST['columnas']);
    var_dump($arrayValues);


    foreach ($arrayValues as $value) {

        $html .= "$value<input type='text' name='$value'><br>";
    }
    $html .= "<input type='hidden' name='pKey' value='$pKey'>";
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>




        <fieldset>

            <legend>modificar</legend>
            <form class="" action="listado.php" method="post">
                <?= $html ?>
                <input type="submit" name="submit" value="modificar">
                <input type="submit" name="submit" value="atras">


            </form>
        </fieldset>



    </body>
</html>
