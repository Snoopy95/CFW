<?php

namespace App\Controller;

use App\Entity\Dossier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * @Route("/checkdossier", name="checkdossier")
     */
    public function checkdossier(Request $request): Response
    {
        $data = $request->getContent();
        $dossier = $this->getDoctrine()->getRepository(Dossier::class)->findby(
            ['numdossier' => $data]
        );
        if (!empty($dossier)) {
            $encoder = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoder);

            $objSerial = $serializer->serialize($dossier, 'json');

            return $this->json([
                $objSerial
            ], 200);
        }
        return new Response('Ce numero de dossier n\'existe pas !!!', 400);
    }
}
