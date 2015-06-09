<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function dashboardAction()
    {
        /** @var $router \Symfony\Component\Routing\Router */
        $router = $this->container->get('router');
        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes  = $collection->all();
        $routes     = array();
        foreach ($allRoutes as $route) {
            if ($route->getOption('menu')) {
                $routes[] = $route->getPath();
            }
        }


        return $this->render('AppBundle:common:dashboard.html.twig', array('routes' => $routes));
    }
}
