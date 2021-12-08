<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT as JWT;

require "IAuto.php";

class Auto implements IAuto
{
    public $id;
    public $color;
    public $marca;
    public $precio;
    public $modelo;


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Parte 1


    public function AltaAuto(Request $request, Response $response, array $args): Response 
	{
        $arrayDeParametros = $request->getParsedBody();

		$json = json_decode($arrayDeParametros['auto']);
        
        $miAuto = new Auto();
        $miAuto -> marca    = $json -> marca;	
        $miAuto -> color    = $json -> color;
        $miAuto -> precio   = $json -> precio;
        $miAuto -> modelo   = $json -> modelo;	

        $retorno = new stdClass();
        $retorno -> exito   = false;
        $retorno -> status  = 418;
        $retorno -> mensaje = "Error al ingresar el auto en la base de datos...";
        $newResponse = $response->withStatus(418);

        $id_agregado = $miAuto -> InsertarAuto($miAuto);

        if($id_agregado > 0)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Exito al ingresar el auto en la base de datos!";
            $newResponse = $response->withStatus(200, "OK");
        }
		
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function InsertarAuto($miAuto)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("INSERT into autos (id, color, marca, precio, modelo)
                                                                values(:id, :color, :marca, :precio, :modelo)");

        $consulta -> bindValue(':id'      , 0, PDO::PARAM_INT);
        $consulta -> bindValue(':color'   , $miAuto -> color  , PDO::PARAM_STR);
        $consulta -> bindValue(':marca'   , $miAuto -> marca   , PDO::PARAM_STR);
		$consulta -> bindValue(':precio'  , $miAuto -> precio  , PDO::PARAM_STR);
        $consulta -> bindValue(':modelo'  , $miAuto -> modelo  , PDO::PARAM_STR);
        
		$consulta -> execute();	

		return $objetoAccesoDato -> RetornarUltimoIdInsertado();
	}
    
    public function ListarAutos(Request $request, Response $response, array $args): Response 
	{
		$todosLosAutos = Auto::TraerTodosLosAutos();

        $retorno = new stdClass();
        $retorno -> exito   = false;
        $retorno -> status  = 424;
        $retorno -> mensaje = "Error no se encontraron autos en la base de datos...";
        $retorno -> dato    = "No hay datos...";
        $newResponse = $response->withStatus(424);

        echo json_encode($todosLosAutos);

        if($todosLosAutos != false)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Exito al traer los autos de a base de datos!";
            $retorno -> dato    = json_encode($todosLosAutos);
            $newResponse = $response->withStatus(200, "OK");
        }
		
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');	
	}

    public static function TraerTodosLosAutos()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

		$consulta = $objetoAccesoDato -> RetornarConsulta("select id, marca as marca, color as color, precio as precio, modelo as modelo from autos");

		$consulta -> execute();			

		return $consulta -> fetchAll(PDO::FETCH_CLASS, "Auto");		
	}

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Parte 2

    public function BorrarAuto(Request $request, Response $response, array $args): Response 
	{
        $retorno = new stdClass();
        $id = $args["id_auto"];

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

        $consulta = $objetoAccesoDato -> RetornarConsulta("DELETE FROM autos WHERE id=".$id."");

        $consulta -> execute();
        $cuenta = $consulta->rowCount();
        
        if($cuenta > 0)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Se pudo borrar de la base de datos!!!";
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $retorno -> exito   = false;
            $retorno -> status  = 418;
            $retorno -> mensaje = "No se pudo borrar de la base de datos...";
            $newResponse = $response->withStatus(418);
        }
		
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function ModificarAuto(Request $request, Response $response, array $args): Response 
	{
        $retorno = new stdClass();
        $auto = json_decode($args["auto"]);

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE autos SET color=:color,marca=:marca,precio=:precio,modelo=:modelo WHERE id=:id");

        $consulta -> bindValue(':id'      , $auto -> id_auto, PDO::PARAM_INT);
        $consulta -> bindValue(':color'   , $auto -> color   , PDO::PARAM_STR);
        $consulta -> bindValue(':marca'   , $auto -> marca   , PDO::PARAM_STR);
        $consulta -> bindValue(':precio'  , $auto -> precio  , PDO::PARAM_STR);
        $consulta -> bindValue(':modelo'  , $auto -> modelo  , PDO::PARAM_STR);

        $consulta -> execute();
        $cuenta = $consulta->rowCount();
        
        if($cuenta > 0)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Se pudo modificar el auto de la base de datos!!!";
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $retorno -> exito   = false;
            $retorno -> status  = 418;
            $retorno -> mensaje = "No se pudo modificar el auto de la base de datos...";
            $newResponse = $response->withStatus(418);
        }
		
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }
}