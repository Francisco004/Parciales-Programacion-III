<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT as JWT;
use Firebase\JWT\Key;
use Psr\Log\NullLogger;

require "IUsuario.php";

class Usuario implements IUsuario
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Parte 1

    public function AltaUsuario(Request $request, Response $response, array $args): Response 
	{
        $arrayDeParametros = $request->getParsedBody();

		$json = json_decode($arrayDeParametros['usuario']);     

        $path = "";
        
        $miUsuario = new Usuario();
        $miUsuario -> clave    = $json -> clave;
		$miUsuario -> correo   = $json -> correo;	
        $miUsuario -> nombre   = $json -> nombre;
        $miUsuario -> perfil   = $json -> perfil;
		$miUsuario -> apellido = $json -> apellido;	

        $retorno = new stdClass();
        $retorno -> exito   = false;
        $retorno -> status  = 418;
        $retorno -> mensaje = "Error al ingresar el usuario en la base de datos...";

		$archivos = $request->getUploadedFiles();
        $destino = __DIR__ . "/../fotos/";

        $nombreAnterior = $archivos['foto']->getClientFilename();
        $extension = explode(".", $nombreAnterior);

        $extension = array_reverse($extension);

        $path = $miUsuario -> correo . "_";

        $id_agregado = $miUsuario->InsertarUsuario($miUsuario, $path, $extension);

		$archivos['foto'] -> moveTo($destino . $miUsuario -> correo . "_" . $id_agregado  . "." . $extension[0]);

        $newResponse = $response->withStatus(418);

        if($id_agregado > 0)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Exito al ingresar el usuario en la base de datos!";

            $newResponse = $response->withStatus(200, "OK");
        }
		
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }

    public function InsertarUsuario($miUsuario, $path, $extension)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (id, foto, clave, nombre, correo, perfil, apellido)
                                                                values(:id, :foto, :clave, :nombre, :correo, :perfil, :apellido)");

        $consulta -> bindValue(':id'      , 0, PDO::PARAM_INT);
        $consulta -> bindValue(':foto'    , 0, PDO::PARAM_STR);
        $consulta -> bindValue(':clave'   , $miUsuario -> clave   , PDO::PARAM_STR);
		$consulta -> bindValue(':perfil'  , $miUsuario -> perfil  , PDO::PARAM_STR);
        $consulta -> bindValue(':correo'  , $miUsuario -> correo  , PDO::PARAM_STR);
        $consulta -> bindValue(':nombre'  , $miUsuario -> nombre  , PDO::PARAM_INT);
        $consulta -> bindValue(':apellido', $miUsuario -> apellido, PDO::PARAM_STR);

		$consulta -> execute();	

        $retorno = $objetoAccesoDato -> RetornarUltimoIdInsertado();

        if($retorno)
        {
            $todosLosUsuarios = Usuario::TraerTodoLosUsuarios();

            if($todosLosUsuarios != false)
            {
                $lastId = $todosLosUsuarios[count($todosLosUsuarios)-1]->id;
            }  

            $pathxd =  "./fotos/".$path.$lastId.".".$extension[0];

            $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET foto='".$pathxd."' WHERE id=".$lastId."");

            $consulta -> execute();	
        }

		return $retorno;
	}

    public function ListarUsuarios(Request $request, Response $response, array $args): Response 
	{
		$todosLosUsuarios = Usuario::TraerTodoLosUsuarios();

        $retorno = new stdClass();
        $retorno -> exito   = false;
        $retorno -> status  = 424;
        $retorno -> mensaje = "Error no se encontraron usuarios en la base de datos...";
        $retorno -> dato    = "No hay datos...";
        $newResponse = $response->withStatus(424);

        if($todosLosUsuarios != false)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Exito al traer los usuarios de a base de datos!";
            $retorno -> dato    = json_encode($todosLosUsuarios);
            $newResponse = $response->withStatus(200, "OK");
        }
  
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');	
	}

    public static function TraerTodoLosUsuarios()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

		$consulta = $objetoAccesoDato -> RetornarConsulta("select id, foto as foto, clave as clave, nombre as nombre, correo as correo, perfil as perfil, apellido as apellido from usuarios");

		$consulta -> execute();			

		return $consulta -> fetchAll(PDO::FETCH_CLASS, "Usuario");		
	}

    public function LoginUsuarioJson(Request $request, Response $response, array $args): Response 
	{
        $arrayDeParametros = $request->getParsedBody();

		$json = json_decode($arrayDeParametros['user']);   

        $retorno = new stdClass();
        $retorno -> status = 403;
        $retorno -> exito   = false;
        $retorno -> usuario = null;
        $newResponse = $response->withStatus(403);

		$elUsuario = Usuario::TraerUnUsuario($json -> correo, $json -> clave);

        if($elUsuario != false)
        {
            $retorno -> status = 200;  
            $retorno -> exito  = true;
            $retorno -> usuario    = json_encode($elUsuario);
            $newResponse = $response->withStatus(200, "OK");
        }

        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');
	}

    public static function TraerUnUsuario($correo, $clave) 
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

		$consulta = $objetoAccesoDato -> RetornarConsulta("select id, foto as foto, nombre as nombre, correo as correo, perfil as perfil, apellido as apellido from usuarios WHERE correo = '".$correo."' AND clave = '".$clave."'");

		$consulta->execute();

		$usuarioBuscado = $consulta->fetchObject('Usuario');

		return $usuarioBuscado;		
	}

    public function BorrarUsuario(Request $request, Response $response, array $args): Response 
	{
        $retorno = new stdClass();

        $arrayDeParametros = $request->getParsedBody();
		$json = json_decode($arrayDeParametros['usuario']);

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

        $consulta = $objetoAccesoDato -> RetornarConsulta("DELETE FROM usuarios WHERE id=".$json->id_usuario."");

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

    public function ModificarUsuario(Request $request, Response $response, array $args): Response 
	{
        $retorno = new stdClass();

        $arrayDeParametros = $request->getParsedBody();
		$usuario = json_decode($arrayDeParametros['usuario']);

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET correo=:correo,clave=:clave,nombre=:nombre,apellido=:apellido,perfil=:perfil,foto=:foto WHERE id=:id");

        $consulta -> bindValue(':id'      , $usuario -> id_usuario  , PDO::PARAM_INT);
        $consulta -> bindValue(':correo'  , $usuario -> correo      , PDO::PARAM_STR);
        $consulta -> bindValue(':clave'   , $usuario -> clave       , PDO::PARAM_STR);
        $consulta -> bindValue(':nombre'  , $usuario -> nombre      , PDO::PARAM_STR);
        $consulta -> bindValue(':apellido', $usuario -> apellido    , PDO::PARAM_STR);
        $consulta -> bindValue(':perfil'  , $usuario -> perfil      , PDO::PARAM_STR);

        $archivos = $request->getUploadedFiles();
        $destino = __DIR__ . "/../fotos/";

        $nombreAnterior = $archivos['foto']->getClientFilename();
        $extension = explode(".", $nombreAnterior);

        $extension = array_reverse($extension);

		$archivos['foto'] -> moveTo($destino . $usuario -> correo . "_" . $usuario -> id_usuario . "_modificacion" . "." . $extension[0]);

        $consulta -> bindValue(':foto'  , "./fotos/" . $usuario -> correo . "_" . $usuario -> id_usuario  . "_modificacion" . "." . $extension[0]      , PDO::PARAM_STR);

        $consulta -> execute();
        $cuenta = $consulta->rowCount();
        
        if($cuenta > 0)
        {
            $retorno -> exito   = true;
            $retorno -> status  = 200;
            $retorno -> mensaje = "Se pudo modificar el usuario de la base de datos!!!";
            $newResponse = $response->withStatus(200);
        }
        else
        {
            $retorno -> exito   = false;
            $retorno -> status  = 418;
            $retorno -> mensaje = "No se pudo modificar el usuario de la base de datos...";
            $newResponse = $response->withStatus(418);
        }
		
        $newResponse->getBody()->write(json_encode($retorno));

        return $newResponse->withHeader('Content-Type', 'application/json');
    }
}