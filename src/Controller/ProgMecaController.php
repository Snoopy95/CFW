<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\ProgMeca;
use App\Form\AddProgType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\AST\Functions\UpperFunction;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProgMecaController extends AbstractController
{
    /**
     * @Route("/fraisage", name="fraisage")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $listes = $this->getDoctrine()->getRepository(ProgMeca::class)->findBy(
            [],
            ['datecreat' => 'DESC'],
            10
        );
        $addprog = new ProgMeca();
        $form = $this->createForm(AddProgType::class, $addprog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addprog->setDatecreat(new \DateTime());
            $client = $addprog->getClient();
            $lastclient = $this->getDoctrine()->getRepository(ProgMeca::class)->findOneBy(
                ['client' => $client],
                ['compteur' => 'DESC']
            );
            if (empty($lastclient)) {
                $addprog->setCompteur(1000);
                $compteur = 1000;
            } else {
                $lastcompt = $lastclient->getCompteur();
                $compteur = $lastcompt + 1;
                $addprog->setCompteur($compteur);
            }
            $typemachine = (isset($_POST['typemachine']));
            $addprog->setTypemachine($typemachine);
            if ($typemachine === 'fraisage') {
                $type = 'F';
            } else {
                $type = 'T';
            }
            $numprog = $type . strtoupper(substr($client, 0, 4)) . $compteur;
            $addprog->setNumprog($numprog);
            $retourplan = $addprog->getRetourplan();
            $file = $form->get('plan')->getData();
            if ($retourplan) {
                dd('il y a un plan');
            }
            if ($file) {
                dd('il  y a un fichier');
            }
            dd('sinon rien');

            $em->persist($addprog);
            $em->flush();
            $this->addFlash('success', 'Prog bien enregistré');

            return $this->redirectToRoute('fraisage');
        }

        return $this->render('progmeca/index.html.twig', [
            'listes' => $listes,
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

            // return new Response('hello world', 200);
            return $this->Json($objSerial, 200);
        }
        return new Response('Le dossier ' . $data . ' n\'existe pas !!!', 400);
    }
}
