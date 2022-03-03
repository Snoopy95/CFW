<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() :Response
    {
        // page de choix du services
        // couper le controle pour separer les services

        return $this->redirectToRoute('accueil');
        // return $this->redirectToRoute('dossiert');
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil() :Response
    {
        # code...
        return $this->render("home/accueil.html.twig", [
        
        ]);
    }
}
