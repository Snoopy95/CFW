<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
