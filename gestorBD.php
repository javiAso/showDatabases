<?php

class gestorBD {

    private $con;
    private $stat;

    public function __construct($host, $user, $pass, $database = false) {



        $this->con = $this->conectar($host, $user, $pass, $database);
    }

    public function conectar($host, $user, $pass, $database): mysqli {

        if ($database) {
            $db = new mysqli($host, $user, $pass, $database);
        } else {
            $db = new mysqli($host, $user, $pass);
        }


        if ($db->errno === 0) {

            $this->stat = 'conectao<br>';
        } else {

            $this->stat = 'no conectao<br>';
        }

        return $db;
    }

    public function consultarBD(): string {

        $rtdo = $this->con->query('SHOW DATABASES');

        $cadena = "";

        while ($row = $rtdo->fetch_assoc()) {



            foreach ($row as $value) {

                $cadena .= "<input type='submit' name='verBase' value='$value'><br>";
            }
        }

        $cadena .= "<br><br><input type='submit' name='submit' value='Volver'><br>";

        return $cadena;
    }

    public function consultarTablas($database): string {

        $rtdo = $this->con->query("show TABLES FROM $database");

        $cadena = "";

        while ($row = $rtdo->fetch_assoc()) {



            foreach ($row as $value) {

                $cadena .= "<input type='submit' name='tabla' value='$value'><br>";
            }
            // $cadena .= "<input type='hidden' name='database' value='$database'><br>";
        }

        $cadena .= "<br><br><input type='submit' name='submit' value='Volver'><br>";
        return $cadena;
    }

    public function consultarTabla($tabla, $database): string {



        $rtdo = $this->con->query("SELECT * FROM $tabla");


        $pKeyIndex = $this->sacarPKey($tabla);


        $cadena = "<table border='1'>";

        $row = $rtdo->fetch_assoc();
        $pKeyValue = $row[$pKeyIndex];
        var_dump($row);
        var_dump($rtdo);

        $cadena .= "<thead><tr>";
        $arrayValues = array();
        $campoParaBorrar = "";

        foreach ($row as $key => $value) {
            $cadena .= "<th>" . $key . "</th>";
            $arrayValues[] = $key;
        }
        $valuesSerializados = serialize($arrayValues);
        $cadena .= "<th>borrar</th>";
        $cadena .= "<th>modificar</th>";
        $cadena .= "</tr></thead><tbody><tr>";
        foreach ($row as $value) {
            $cadena .= "<td>" . $value . "</td>";
        }
        $cadena .= "<td><input type='submit' name='borrar' value='borrar $pKeyIndex : $pKeyValue'></td>";
        $cadena .= "<td><div><form id='form1' action='modificar.php' method='post'>"
                . "<input type='submit' name='$pKeyIndex $pKeyValue' value='modificar'>"
                . "<input type='submit' name='probando' value='probando'>"
                . "<input type='hidden' name='columnas' value='$valuesSerializados'>"
                . "</form></div></td>";
        //$cadena .= "<td><input type='hidden' name='pKey' value='$pKeyValue'></td>";
        $cadena .= "</tr>";
        while ($row = $rtdo->fetch_assoc()) {



            $cadena .= "<tr>";

            foreach ($row as $value) {
                $pKeyValue = $row[$pKeyIndex];
                $cadena .= "<td>$value</td>";
            }

            $cadena .= "<td><input type='submit' name='borrar' value='borrar $pKeyIndex : $pKeyValue'></td>";
            $cadena .= "<td><form action='modificar.php' method='post'>"
                    . "<input type='submit' name='$pKeyIndex $pKeyValue' value='modificar'>"
                    . "<input type='hidden' name='columnas' value='$valuesSerializados'>"
                    . "</form></td>";
            //$cadena .= "<td><input type='hidden' name='pKey[]' value='$pKeyValue'></td>";
            $cadena .= "</tr>";
        }

        $cadena .= "</table>";

        foreach ($arrayValues as $dato) {

            // "<input type='hidden' name='arrayValues[]' value='$dato'><br> "
            $cadena .= "$dato<input type='text' name='$dato'><br>";
        }
        $cadena .= "<br><input type='submit' name='insertar' value='insertar'><br>"
                . "<input type='hidden' name='tabla' value='$tabla'><br>"
                . "<input type='submit' name='volver' value='volver'><br>";



        return $cadena;
    }

    public function insertarFila($post, $tabla) {

        $sentencia = "";
        $insercion = "insert into $tabla (";
        $arrayValues = array();

        var_dump($post);

        foreach ($post as $index => $value) {

            if ($index == 'columnas' || $index == 'insertar' || $index == 'tabla') {
                continue;
            }

            $arrayValues[$index] = $value;
        }



        foreach ($arrayValues as $value => $dato) {

            $insercion .= "$value,";
        }

        $insercion = trim($insercion, ',');


        $insercion .= ") values(";

        foreach ($arrayValues as $dato) {

            $insercion .= "'$dato',";
        }

        $insercion = trim($insercion, ',');
        $insercion .= ")";


        $rtdo = $this->con->query($insercion);

        if ($rtdo)
        //Se le pone llaves para evitar la concatenación, que puede resultar incómoda, y menos legilb.e
            $this->stat .= "Se ha insertado una fila {$this->con->affected_rows}";
        else
            $this->stat .= "Error, no se ha podido insertar" . $this->con->error;
    }

    private function sacarPKey($tabla) {


        $rtdo = $this->con->query("select * from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where Table_Name = '$tabla' ");

        $row = $rtdo->fetch_assoc();
        var_dump($row);
        var_dump($rtdo);
        return $row['COLUMN_NAME'];
    }

    public function borrar($tabla, $borrar) {

        $array = explode(" ", $borrar);
        $pKeyIndex = $array[1];
        $pKeyValue = $array[3];

        $rtdo = $this->con->query("DELETE FROM $tabla WHERE $pKeyIndex=$pKeyValue");
        if ($rtdo)
        //Se le pone llaves para evitar la concatenación, que puede resultar incómoda, y menos legilb.e
            $this->stat .= "Se ha borrado una fila {$this->con->affected_rows}";
        else
            $this->stat .= "Error, no se ha podido insertar" . $this->con->error;
    }

    public function modificar($post, $tabla) {

        // UPDATE `usuarios` SET `code`=2,`name`='aooo',`password`='aoooo' WHERE code=1


        $sentencia = "";
        $insercion = "UPDATE $tabla SET ";



        foreach ($post as $index => $value) {

            if ($index == 'submit' || $index == 'pKey') {
                continue;
            }

            $insercion .= "$index = '$value',";
        }

        $pKey = explode("_", $_POST['pKey']);

        $insercion = trim($insercion, ',');
        $insercion .= " WHERE $pKey[0] = '$pKey[1]'";

        echo $insercion;


        $rtdo = $this->con->query($insercion);

        if ($rtdo)
        //Se le pone llaves para evitar la concatenación, que puede resultar incómoda, y menos legilb.e
            $this->stat .= "Se ha modificado una fila {$this->con->affected_rows}";
        else
            $this->stat .= "Error, no se ha podido modificar" . $this->con->error;

        //var_dump($post);
    }

    public function cerrar() {
        $this->con->close();
    }

    public function __toString() {
        return $this->stat;
    }

}
