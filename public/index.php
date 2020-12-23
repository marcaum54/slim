<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use App\Helpers\SondaEspacial;

require __DIR__ . '/../vendor/autoload.php';


$app = AppFactory::create();
$app->addErrorMiddleware($displayErrorDetails = true, $logErrors = true, $logErrorDetails = true);

$app->get('/', function (Request $request, Response $response, $args)
{
    $response->getBody()->write("Credere Sonda API: online");
    return $response;
});

$app->get('/resetar', function (Request $request, Response $response, $args)
{
    $sonda = new SondaEspacial;
    $sonda->resetar();

    $response->getBody()->write('');
    return $response->withStatus(200);
});

$app->get('/executar-comandos[/{comandos:.*}]', function (Request $request, Response $response, $args)
{
    $comandos = array_filter(explode('/', trim($args['comandos'])));

    $sonda = new SondaEspacial;
    $result = $sonda->executarComandos($comandos, isset($_GET['debug']));
    $json = json_encode($result);

    $response->getBody()->write($json);

    if(!isset($_GET['debug']))
        $response = $response->withHeader('Content-type', 'application/json');

    return $response;
});

$app->get('/posicao-atual', function (Request $request, Response $response, $args)
{
    $sonda = new SondaEspacial;
    $json = json_encode($sonda->getPosicaoAtual());

    $response->getBody()->write($json);
    return $response->withHeader('Content-type', 'application/json');
});

$app->run();
