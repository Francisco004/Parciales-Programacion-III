<?php

    include "./clases/Receta.php";

    $retorno = new stdClass();
    $retorno->mensaje = "No se encontro la receta en la base de datos...";
    $retorno->retorno = false;

    $modificar = json_decode($_POST["receta_json"],true);

    $extension = $_FILES["foto"]["name"];
    $tmpsNames = $_FILES["foto"]["tmp_name"];
    $tiposArchivo = pathinfo($extension, PATHINFO_EXTENSION);

    $hoy = date("H:i:s"); 
    $hoy = str_replace(":","",$hoy);

    $destino = "./recetasModificadas/".$modificar["nombre"].".".$modificar["tipo"]."."."modificado.".$hoy.".".$tiposArchivo;
    $modificarFoto = $modificar["nombre"].".".$modificar["tipo"]."."."modificado.".$hoy.".".$tiposArchivo;

    $producto = new Receta($modificar["nombre"],$modificar["ingredientes"],$modificar["tipo"],$modificar["id"],$modificarFoto);

    if($producto->Modificar())
    {
        rename($tmpsNames, $destino);
        
        $retorno->mensaje = "Exito al modificar la receta en la base de datos!!";
        $retorno->retorno = true;
    }

    echo json_encode($retorno);
?>