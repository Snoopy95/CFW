<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\ProgMeca;
use App\Form\AddProgType;
use Doctrine\ORM\EntityManagerInterface;
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
            $typemachine = $_POST["typemachine"];
            if (!isset($typemachine)) {
                $typemachine = 'fraisage';
            }
            if ($typemachine === 'fraisage') {
                $type = 'F';
            } else {
                $type = 'T';
            }
            $addprog->setTypemachine($typemachine);
            $lastnum = $type . strtoupper(substr($client, 0, 4));
            $lastprog = $this->getDoctrine()->getRepository(ProgMeca::class)->findByNumprog($lastnum);
            if (empty($lastprog)) {
                $compteur = 1000;
            } else {
                $compteur = $lastprog->getCompteur();
                $compteur++;
            }
            $addprog->setCompteur($compteur);
            $numprog = $lastnum . $compteur;

            $addprog->setNumprog($numprog);
            $retourplan = $addprog->getRetourplan();
            $file = $form->get('plan')->getData();
            if ($retourplan) {
                //dd('il y a un plan');
            }
            if ($file) {
                //dd('il  y a un fichier');
            }
            //dd('sinon rien');
            // dd($addprog, $file);

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
