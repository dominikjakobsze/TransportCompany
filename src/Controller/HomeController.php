<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        return $this->render('base.html.twig');
    }
}