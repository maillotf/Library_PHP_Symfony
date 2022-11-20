<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RootController extends AbstractController
{
    #[Route('/', name: 'root', methods:'GET')]
    public function root(): JsonResponse
    {
        $router = $this->container->get('router');
        $routesList = $router->getRouteCollection()->all();

        $routes = array();
        foreach ($routesList as $routeName => $params)
        {
            $routes[] = [
                'name' => $routeName,
                'method' => (empty($methods = $params->getMethods()))?'ANY':implode(',', $methods),
                'scheme' => (empty($schemes = $params->getSchemes()))?'ANY':implode(',', $schemes),
                'host' => (empty($host = $params->getHost()))?'ANT':$host,
                'path' => $params->getPath()
            ];
        }

        return JsonResponse::fromJsonString(json_encode($routes, JSON_UNESCAPED_SLASHES), 200);
    }
}
