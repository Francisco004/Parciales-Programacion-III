<?php

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "No se encontro una coockie con este email y especialidad";

    $mailGuion = $_GET["email"]."_".$_GET["especialidad"];

    $mailConGuion = str_replace(".","_",$mailGuion);

    if(isset($_COOKIE[$mailConGuion]) || isset($_COOKIE[$mailGuion]))
    {
        $retorno->exito = true;
        $retorno->mensaje = "Coockie:".$_COOKIE[$mailConGuion];
    }

    echo json_encode($retorno);

?>