<?php
    include "./clases/Receta.php";
    
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "No se recibio ningun get ni post corretos...";
    
    if(isset($_GET["nombre"]))
    {
        $array = Receta::Traer();

        for($i = 0; $i < count($array); $i++)
        {
            if($array[$i]->nombre == $_GET["nombre"])
            {
                $retorno->exito = true;
                $retorno->mensaje = "El nombre se encuentra en la base de datos!";
                break;
            }
            else if ($i == count($array) - 1)
            {
                $retorno->exito = false;
                $retorno->mensaje = "El nombre no se encuentra en la base de datos!";
            }
        }

        echo json_encode($retorno);
    }
    else if(isset($_POST["receta_json"]) && isset($_POST["accion"]) && $_POST["accion"] == "borrar")
    {
        $datos = json_decode($_POST["receta_json"]);

        $listado = json_encode(Receta::Traer());
        $listado = json_decode($listado);

        $receta = null;

        for($i = 0; $i < count($listado); $i++)
        {
            if($listado[$i]->nombre == $datos->nombre && $listado[$i]->tipo == $datos->tipo)
            {
                $receta = new Receta($listado[$i]->nombre, $listado[$i]->ingredientes, $listado[$i]->tipo,$listado[$i]->id, $listado[$i]->pathFoto);
            }
        }

        if($receta != null && $receta->Eliminar())
        {
            $receta->GuardarEnArchivo();

            $retorno->exito = true;
            $retorno->mensaje = "Exito al eliminar la receta de la base de datos";

        }
        else
        {
            $retorno->exito = false;
            $retorno->mensaje = "No se encontro la receta en la base de datos...";
        }

        echo json_encode($retorno);
    }
    else
    {
        Receta::MostrarBorrados();
    }
?>
