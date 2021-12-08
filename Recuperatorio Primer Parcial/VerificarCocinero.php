<?php

    include "./clases/Cocinero.php";
    
    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "No se encontro el cocinero en el listado de cocineros ";
    
    $cocinero = new Cocinero("xd",$_POST["email"],$_POST["clave"]);

    $verificacion = json_decode(Cocinero::VerificarExistencia($cocinero));

    $retorno->mensaje .= $verificacion->mensaje;

    if($verificacion->exito == true)
    {
        $listaJSON = Cocinero::TraerTodos();

        foreach($listaJSON as $lista)
        {
            if($lista->email == $_POST["email"] && $lista->clave == $_POST["clave"])
            {
                $especialidad = $lista->especialidad;
                break;
            }
        }

        $mensaje = date("His")." ".$verificacion->mensaje;
        setcookie($_POST["email"]."_".$especialidad,$mensaje);

        $retorno->exito = true;
        $retorno->mensaje = "Se creo una coockie con el email, especialidad, fecha y mensaje del cocinero " . $verificacion->mensaje;
    }

    echo json_encode($retorno);
  
?>
