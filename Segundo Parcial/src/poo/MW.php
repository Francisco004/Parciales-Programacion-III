<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use \Firebase\JWT\JWT as JWT;
    use Firebase\JWT\Key;
    use Psr\Log\NullLogger;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response as ResponseMW;
    use Slim\Factory\AppFactory;
    use \Slim\Routing\RouteCollectorProxy;

    class MW
    {

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Parte 2

        public static function EstanVacios(Request $request, RequestHandler $handler):ResponseMW
        {
            $retorno = new stdClass();
            $retorno -> status  = 409;
            $retorno -> mensaje = "El correo y la clave se encuentran vacios...";

            $datosUsuario = $request->getParsedBody();

            if(isset($datosUsuario['usuario']))
            {
                $json = json_decode($datosUsuario['usuario']);
            }
            else
            {
                $json = json_decode($datosUsuario['user']);
            }
            
            $response = new ResponseMW();

            if($json -> correo == "" && $json -> clave == "")
            {
                $response -> getBody() -> write(json_encode($retorno));
                $response -> withStatus(409);
            }
            else if($json -> correo == "")
            {
                $retorno -> mensaje = "El correo se encuentra vacio...";
                $response -> getBody() -> write(json_encode($retorno));
                $response -> withStatus(409);
            }
            else if($json -> clave == "")
            {
                $retorno -> mensaje = "La clave se encuentra vacia...";
                $response -> getBody() -> write(json_encode($retorno));
                $response -> withStatus(409);
            }
            else
            {
                $response = $handler -> handle($request);
            }

            return $response;
        }

        public function EstanDB(Request $request, RequestHandler $handler):ResponseMW
        {
            $retorno = new stdClass();
            $retorno -> status  = 403;
            $retorno -> mensaje = "Correo y Clave NO estan en la BD..";

            $datosUsuario = $request -> getParsedBody();

            $json = json_decode($datosUsuario['user']);

            $response = new ResponseMW();
		
            if(Usuario::TraerUnUsuario($json -> correo, $json -> clave) == false)
            {
                $response -> getBody() -> write(json_encode($retorno));
                $response -> withStatus(403);
            }
            else
            {
                $response = $handler -> handle($request);
            }
            
            return $response;
        }

        public static function NoEstanDB(Request $request, RequestHandler $handler):ResponseMW
        {
            $retorno = new stdClass();
            $retorno -> status  = 403;
            $retorno -> mensaje = "El correo ya esta en la base de datos..";

            $datosUsuario = $request->getParsedBody();
            $json = json_decode($datosUsuario['usuario']);
            
            $response = new ResponseMW();

            if(Usuario::TraerUnUsuario($json -> correo, $json -> clave))
            {
                $response = $response -> withStatus(403);
        
                $response -> getBody() -> write(json_encode($retorno));
            }
            else
            {
                $response = $handler -> handle($request);
            }

            return $response;
        }
    }
?>