<?php

    include "./clases/ProductoEnvasado.php";

    $retorno = new stdClass();
    $retorno->mensaje = "Error al agregar el producto en la base de datos...";
    $retorno->retorno = false;

    $decodeo = json_decode($_POST["producto_json"]);

    $producto = new ProductoEnvasado($decodeo->nombre, $decodeo->origen, $decodeo->codigoBarra, $decodeo->precio);

    if($producto->Agregar())
    {
        $retorno->mensaje = "Exito al agregar el producto en la base de datos!!";
        $retorno->retorno = true;
    }
    
    return json_encode($retorno);

?>