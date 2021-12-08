<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use \Slim\Routing\RouteCollectorProxy;
    use Slim\Factory\AppFactory;

    require __DIR__ . '../../../vendor/autoload.php';

    $scaloneta = AppFactory::create();

    require "../src/poo/AccesoDatos.php";
    require "../src/poo/auto.php";
    require "../src/poo/usuario.php";
    require "../src/poo/MW.php";

    $scaloneta->post('/usuarios', \Usuario::class . ':AltaUsuario') -> add(\MW::class . '::NoEstanDB') -> add(\MW::class . '::EstanVacios');
    $scaloneta->get('/', Usuario::class . ':ListarUsuarios');

    $scaloneta->post('/', \Auto::class . ':AltaAuto');
    $scaloneta->get('/autos', Auto::class . ':ListarAutos');

    $scaloneta->post('/login', Usuario::class . ':LoginUsuarioJson') -> add(\MW::class . '::EstanDB') -> add(\MW::class . '::EstanVacios');


    $scaloneta->group('/cars', function (\Slim\Routing\RouteCollectorProxy $cars) {   

        $cars->delete('/{id_auto}',\Auto::class . ':BorrarAuto');
        $cars->put('/{auto}',\Auto::class . ':ModificarAuto');
    });
    
    $scaloneta->group('/users', function (\Slim\Routing\RouteCollectorProxy $cars) {   

        $cars->post('/delete',\Usuario::class . ':BorrarUsuario');
        $cars->post('/edit',\Usuario::class . ':ModificarUsuario');
    });

    $scaloneta->group('/tablas', function (\Slim\Routing\RouteCollectorProxy $cars) {   

        $cars->get('/usuarios',\Usuario::class . ':TablaUsuario');
        $cars->post('/usuarios',\Usuario::class . ':PDFUsuario');
        $cars->post('/autos',\Usuario::class . ':ListarAutos');
    });

    $scaloneta->run();

?>