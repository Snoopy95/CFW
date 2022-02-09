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
            $typemachine = $addprog->getTypemachine();
            if ($typemachine === 'Fraisage') {
                $type = 'F';
            } else {
                $type = 'T';
            }
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
            $plan_name = $numprog.'-PLAN.pdf';
            $step_name = $numprog.'-3D.step';

            $retourplan = $addprog->getRetourplan();
            $retourstep = $addprog->getRetourstep();
            $plan = $form->get('plan')->getData();
            $step = $form->get('step')->getData();

            if ($retourplan) {
                copy('dossier/plan/'.$retourplan, 'meca/plan/'.$plan_name);
                $addprog->setPlan($plan_name);
            }
            if ($retourstep) {
                copy('dossier/3D/'.$retourstep, 'meca/3D/'.$step_name);
                $addprog->setStep($step_name);
            }
            if ($plan) {
                $plan->move('meca/plan/'.$plan_name);
                $addprog->setPlan($plan_name);
            }
            if ($step) {
                $step->move('meca/3D/'.$step_name);
                $addprog->setStep($step_name);
            }

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
        $dossier = $this->getDoctrine()->getRepository(Dossier::class)->findBy(
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

    /**
     * @Route("listeprog/{mac}", name="listeprog")
     */
    public function listeprog($mac): Response
    {
        switch ($mac) {
            case 'fraise':
                $type = 'Fraisage';
                break;
            case 'tour':
                $type = 'Tournage';
                break;
            case 'all':
                $type = null;
                break;
        }
        if ($type) {
            $listes = $this->getDoctrine()->getRepository(ProgMeca::class)->findBy(
                ['typemachine' => $type],
                ['datecreat' => 'DESC']
            );
        } else {
            $listes = $this->getDoctrine()->getRepository(ProgMeca::class)->findBy(
                [],
                ['datecreat' => 'DESC']
            );
        }

        $clients = [];
        foreach ($listes as $value) {
            $client = strtoupper($value->getClient());
            if (empty($clients)) {
                $clients[] = $client;
            } else {
                foreach ($clients as $item) {
                    if ($client === strtoupper($item)) {
                        $add = false;
                        break;
                    }
                    $add = true;
                }
                if ($add) {
                    $clients[] = $client;
                }
            }
        }
        sort($clients);

        return $this->render('progmeca/listeprog.html.twig', [
            'listes' => $listes,
            'clients' => $clients,
            'mac' => $type,
        ]);
    }
    
    /**
     * @Route("filtreclient", name="filtreclient")
     */
    public function filtreclient(Request $request): Response
    {
        $filtre = json_decode($request->getContent(), true);
        if (!empty($filtre)) {
            $machine = $filtre[0];
            $client = $filtre[1];

            $listes = $this->getDoctrine()->getRepository(ProgMeca::class)->findBy(
                ['typemachine' => $machine,
                'client' => $client],
                ['datecreat' => 'DESC']
            );
            $encoder = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoder);

            $objSerial = $serializer->serialize($listes, 'json');
            return $this->Json($objSerial, 200);

            // return new Response('recu : '.$client, 200);
        }
        return new Response('Erreur de data', 400);
    }
}
