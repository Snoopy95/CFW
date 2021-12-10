<?php

namespace App\Controller;

use App\Entity\ProgMeca;
use App\Form\AddProgType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgMecaController extends AbstractController
{
    /**
     * @Route("/fraisage", name="fraisage")
     */
    public function index(Request $request): Response
    {
        $list = $this->getDoctrine()->getRepository(ProgMeca::class)->findBy(
            [],
            ['datecreat' => 'DESC'],
            10
        );
        $addprog = new ProgMeca();
        $form = $this->createForm(AddProgType::class, $addprog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('progmeca/index.html.twig', [
            'list' => $list,
            'form' => $form->createView()
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
