<?php

    include "./clases/Cocinero.php";

    $producto = new Cocinero($_POST["especialidad"],$_POST["email"],$_POST["clave"]);

    $producto->ToJson();

    echo $producto->GuardarEnArchivo();

?>