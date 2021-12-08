<?php

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "No se encontro una coockie con este nombre y origen";

    echo $_GET["nombre"];

    if(isset($_COOKIE[$_GET["nombre"]."_".$_GET["origen"]]))
    {
        $retorno->exito = true;
        $retorno->mensaje = "Coockie:".$_COOKIE[$_GET["nombre"]."_".$_GET["origen"]];
    }

    var_dump($retorno);

    return json_encode($retorno);
?>