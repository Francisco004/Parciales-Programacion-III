<?php

    include "./clases/Producto.php";
    
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "No se encontro el producto en el listado de productos";
    
    $producto = new Producto($_POST["nombre"],$_POST["origen"]);

    $verificacion = json_decode(Producto::VerificarProductoJson($producto));

    if($verificacion->exito == true)
    {
        $fecha = date("Gis");
        setcookie($_POST["nombre"]."_".$_POST["origen"], $fecha);
        //setcookie($_POST["nombre"]."_".$_POST["origen"], $fecha . " " . $verificacion->mensaje);

        $retorno->exito = true;
        $retorno->mensaje = "Se creo una coockie con el nombre, origen, fecha y mensaje del producto " . $verificacion->mensaje;
    }

    var_dump($retorno);

    return json_encode($retorno);

?>