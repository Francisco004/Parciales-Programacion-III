<?php

    include "./clases/ProductoEnvasado.php";

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "Error al eliminar el producto";

    $cadena = json_decode($_POST["producto_json"]);

    if(ProductoEnvasado::Eliminar($cadena->id))
    {
        $producto = new Producto($cadena->nombre,$cadena->origen);
        $producto->GuardarJson("./archivos/productos_eliminados.json");

        $retorno->exito = true;
        $retorno->mensaje = "Exito al eliminar el producto";
    }

    return json_encode($retorno);
?>
