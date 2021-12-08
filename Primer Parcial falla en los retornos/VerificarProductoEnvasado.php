<?php
    include "./clases/ProductoEnvasado.php";

    $retorno = "({})";

    $json = $_POST["obj_producto"];
    $array = ProductoEnvasado::Traer();

    for($i = 0; $i < count($array); $i++)
    {
        if($array[$i]->nombre == $json->nombre && $array[$i]->origen == $json->origen)
        {
            $retorno = $array[$i]->ToJson();
        }
    }

    return json_encode($retorno);
?>