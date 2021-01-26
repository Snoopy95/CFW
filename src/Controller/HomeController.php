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

        return $this->redirectToRoute('acceuil');
        // return $this->redirectToRoute('dossiert');
    }

    /**
     * @Route("/acceuil", name="acceuil")
     */
    public function acceuil() :Response
    {
        # code...
        return $this->render("home/acceuil.html.twig", [
        
        ]);
    }
}
