<?php

    include "./clases/Receta.php";

    $retorno = new stdClass();
    $retorno->mensaje = "Error al agregar la receta en la base de datos...";
    $retorno->retorno = false;

    $receta = new Receta($_POST["nombre"],$_POST["ingredientes"],$_POST["tipo"]);

    if($receta->Agregar())
    {
        $retorno->mensaje = "Exito al agregar la receta en la base de datos!!";
        $retorno->retorno = true;
    }
    
    echo json_encode($retorno);

?>