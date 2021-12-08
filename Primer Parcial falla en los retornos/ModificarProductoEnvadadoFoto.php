<?php
    $rta = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "Error al modificar";

    $modificar = json_decode($_POST["producto_json"],true);

    $extension = $_FILES["foto"]["name"];
    $tmpsNames = $_FILES["foto"]["tmp_name"];
    $tiposArchivo = pathinfo($extension, PATHINFO_EXTENSION);

    $hoy = date("H:i:s"); 
    $hoy = str_replace(":","",$hoy);

    $producto = new ProductoEnvasado($modificar["nombre"],$modificar["origen"],$modificar["codigoBarra"],$modificar["precio"],$tmpsNames,$modificar["id"]);

    if($producto->Modificar())
    {
        $destino = "./productosModificados/".$nombre.".".$origen."."."modificado".$hoy.".".$tiposArchivo;
        rename($this->pathFoto, $destino);
        move_uploaded_file($tmpsNames, $destino);

        $retorno->exito = true;
        $retorno->mensaje = "Exito al modificar";
    }

    return json_encode($retorno);
?>