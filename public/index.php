<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Action\Index;
use App\Action\Tamagochi;
use App\Bus\LifeBus;
use App\Handlers\CircleOfLifeHandler;
use App\Handlers\DatabaseHandler;
use App\Handlers\DoctorHandler;
use App\Handlers\EatHandler;
use App\Handlers\RunHandler;
use App\Handlers\SleepHandler;
use App\Repository\MoutonRepository;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

session_start();

$loader = new FilesystemLoader(__DIR__.'/../Template');
$twig = new Environment($loader);

$request = Request::createFromGlobals();

$repository = new MoutonRepository();
$nn = new \App\NeuralNetwork\Tamagotchi();

$bus = new LifeBus();
$bus->setHandlers([
    new CircleOfLifeHandler(),
    new DatabaseHandler($repository),
    new DoctorHandler(),
    new EatHandler($nn),
    new RunHandler($nn),
    new SleepHandler($nn),
]);

$routes = new RouteCollection();
$routes->add('home', new Route('/', ['_controller' => Index::class]));
$routes->add('tamagotchi', new Route('/tamagotchi', ['_controller' => Tamagochi::class]));

$context = new RequestContext('/');

$matcher = new UrlMatcher($routes, $context);
$parameters = $matcher->match($request->getPathInfo());

$controllerClass = $parameters['_controller'] ?? null;
if (null !== $controllerClass) {
    $controller = new $controllerClass($bus, $repository, $twig);
}

$response = $controller($request);
$response->send();
