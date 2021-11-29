<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FraisageController extends AbstractController
{
    /**
     * @Route("/fraisage", name="fraisage")
     */
    public function index(): Response
    {
        return $this->render('fraisage/index.html.twig', [
            'list' => 'FraisageController',
        ]);
    }
}
