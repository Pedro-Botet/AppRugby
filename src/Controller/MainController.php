<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Página de inicio
     * 
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * Template para los errores
     * 
     * @Route("/", name="error")
     */
    public function error($error)
    {
        if($error == 1){
            $mensaje = 'Haz Login para acceder a tu carrito';
        }
        return $this->render('error.html.twig', [
            'error' => $mensaje
        ]);
    }
}