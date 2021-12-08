<?php

    include "./clases/ProductoEnvasado.php";

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "Error al modificar el producto";

    $cadena = json_decode($_POST["producto_json"]);

    $producto = new ProductoEnvasado($cadena->nombre, $cadena->origen, $cadena->codigoBarra, $cadena->precio, $cadena->id);

    if($producto->Modificar())
    {
        $retorno->exito = true;
        $retorno->mensaje = "Exito al modificar el producto";
    }

    return json_encode($retorno);
?>