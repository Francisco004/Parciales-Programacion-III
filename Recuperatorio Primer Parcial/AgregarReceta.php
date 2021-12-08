<?php

    include "./clases/Receta.php";

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "Error al agregar la receta en la base de datos";

    $nombre = $_POST["nombre"];
    $ingredientes = $_POST["ingredientes"];
    $tipo = $_POST["tipo"];

    $extension = $_FILES["foto"]["name"];
    $tmpsNames = $_FILES["foto"]["tmp_name"];
    $tiposArchivo = pathinfo($extension, PATHINFO_EXTENSION);

    $hoy = date("H:i:s"); 
    $hoy = str_replace(":","",$hoy);
    $destino = "./recetas/imagenes/".$nombre.".".$tipo.".".$hoy.".".$tiposArchivo;
    $alta = $nombre.".".$tipo.".".$hoy.".".$tiposArchivo;

    $receta = new Receta($nombre,$ingredientes,$tipo,1,$alta);

    if(($receta->Existe(Receta::Traer())))
    {
        move_uploaded_file($tmpsNames, $destino);

        $receta->Agregar();
        
        $retorno->exito = true;
        $retorno->mensaje = "Exito al agregar el receta en la base de datos";
    }
    else
    {
        $retorno->mensaje = "Ya existe esta receta en la base de datos...";
    }

    echo json_encode($retorno);
    
?>