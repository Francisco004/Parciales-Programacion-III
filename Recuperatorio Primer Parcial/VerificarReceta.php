<?php
    include "./clases/Receta.php";
    
    $nombre = 0;
    $tipo = 0;
    
    if(isset($_POST["receta"]))
    {
        $array = Receta::Traer();

        $json = json_decode($_POST["receta"]);

        for($i = 0; $i < count($array); $i++)
        {
            if($array[$i]->nombre == $json->nombre)
            {
                $nombre++;
            }

            if($array[$i]->tipo == $json->tipo)
            {
                $tipo++;
            }

            if($nombre > 0 && $tipo > 0)
            {
                $retorno = $array[$i]->ToJson();
                break;
            }
        }
    }

    if($nombre == 0 && $tipo > 0)
    {
        echo "No se encontro una receta coincidente con este nombre";
    }
    else if($tipo == 0 && $nombre > 0)
    {
        echo "No se encontro una receta coincidente con este tipo";
    }
    else if ($nombre == 0  && $tipo == 0)
    {
        echo "No se encontro una receta coincidente con este nombre y tipo";
    }
    else
    {
        echo json_encode($retorno);
    }
?>