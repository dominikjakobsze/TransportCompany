<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route("/{reactRouting}", name: 'home', requirements: ['reactRouting' => '^(?!api).*$'], defaults: ['reactRouting' => null])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/api/test', name: 'test')]
    public function test()
    {
        dd((new \DateTime('now', new \DateTimeZone('Europe/Warsaw'))));
    }

    #[Route('/api/login', name: 'api_login')]
    public function login()
    {
        dd('/api/login Controller');
    }
}