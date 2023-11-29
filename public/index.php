<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/CuentaController.php';
require_once './controllers/MovimientoController.php';
require_once './controllers/ConsultasController.php';
require_once './controllers/AjusteController.php';
require_once './controllers/LoginController.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/AutentificadorJWT.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//LOGUEO
$app->group('/auth', function (RouteCollectorProxy $group) {

  $group->post('/login[/]', \LoginController::class . ':LoginUsuario');

});

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Segundo Parcial Programacion III");
    return $response;
});

// peticiones 
$app->group('/cuenta', function (RouteCollectorProxy $group) {
    $group->get('[/]', \CuentaController::class . ':TraerTodos');
    $group->post('[/]', \CuentaController::class . ':CargarUno');
    $group->post('/consultar[/]', \CuentaController::class . ':TraerUno');
    $group->put('/modificar[/]', \CuentaController::class . ':ModificarUno');
    $group->delete('/eliminar[/]', \CuentaController::class . ':BorrarUno');
  });

  $app->post('/cuenta/deposito[/]', \MovimientoController::class . ':CargarDeposito')
  ->add(\AuthMiddleware::class. ':verificarToken')
  ->add(\AuthMiddleware::class. ':ValidarCajero');
  $app->post('/cuenta/retiro[/]', \MovimientoController::class . ':CargarRetiro')
  ->add(\AuthMiddleware::class. ':verificarToken')
  ->add(\AuthMiddleware::class. ':ValidarCajero');//CAJERO

  $app->post('/cuenta/ajuste[/]', \AjusteController::class . ':AjustarMovimiento')
  ->add(\AuthMiddleware::class. ':verificarToken')
  ->add(\AuthMiddleware::class. ':ValidarSupervisor'); //supervisor

  $app->group('/consultas', function (RouteCollectorProxy $group) {
    $group->get('/movimiento/{documento}[/]', \ConsultasController::class . ':ListarMovimientosPorUsuario');
    $group->get('/{tipoDeCuenta}[/{fecha}]', \ConsultasController::class . ':TotalRetiradoPorTipoYMonedaEnFecha');
    $group->get('/depositos/cuenta/{documento}[/]', \ConsultasController::class . ':ListarDepositosPorUsuario');
    $group->get('/depositos/fechas/{fechaInicio}_{fechaFin}[/]', \ConsultasController::class . ':ListarDepositosPorFechas');
    $group->get('/depositos/tipo/{tipoDeCuenta}[/]', \ConsultasController::class . ':ListarDepositosPorTipoDeCuenta');
    $group->get('/depositos/moneda/{tipoDeMoneda}[/]', \ConsultasController::class . ':ListarDepositosPorTipoDeMoneda');
  })->add(\AuthMiddleware::class. ':verificarToken')
  ->add(\AuthMiddleware::class. ':ValidarOperador');


// Run app
$app->run();
