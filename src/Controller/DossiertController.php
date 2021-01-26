<?php

namespace App\Controller;

use App\Entity\Backdossier;
use App\Entity\Dossier;
use App\Entity\Search;
use App\Entity\SearchIn;
use App\Form\AddDossierType;
use App\Form\SearchInType;
use App\Form\SearchType;
use App\Form\UpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DossiertController extends AbstractController
{
    /**
     * @Route("/dossiert/{try}", name="dossiert")
     */
    public function dossiert(Request $request, EntityManagerInterface $em, string $try = "nu123"): Response
    {
        switch (substr($try, 0, 2)) {
            case 'nu':
                $colm = 'numdossier';
                break;
            case 'cl':
                $colm = 'client';
                break;
            case 'dc':
                $colm = 'datecreat';
                break;
            default:
                $colm = 'numdossier';
                break;
        }
        switch (substr($try, 2, 3)) {
            case '123':
                $senstry = "ASC";
                break;
            case '321':
                $senstry = 'DESC';
                break;
            default:
                $senstry = 'ASC';
                break;
        }
        $coltry = [$colm => $senstry];
        $listes = $this->getDoctrine()->getRepository(Dossier::class)->findBy(
            [],
            $coltry
        );
        $adddossier = new Dossier();
        $searchin = new SearchIn();

        $form = $this->createform(AddDossierType::class, $adddossier);
        $form->handleRequest($request);
        $formsearch = $this->createform(SearchInType::class, $searchin);
        $formsearch->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adddossier->setDatecreat(new \DateTime());

            $ind = $adddossier->getInd();
            if (empty($ind)) {
                $newfilename =
                    "T" . $adddossier->getNumdossier() . " - " . $adddossier->getRefpiece() . ".pdf";
            } else {
                $newfilename =
                    "T" . $adddossier->getNumdossier() . " - " . $adddossier->getRefpiece() . " - " . $ind . ".pdf";
            }
            $directory = "dossier/plan/";
            $file = $form->get('plan')->getData();
            if (isset($file)) {
                $file->move($directory, $newfilename);
                $adddossier->setPlan($newfilename);
            }

            $em->persist($adddossier);
            $em->flush();
            $this->addFlash('success', 'Dossier bien enregistré');
            return $this->redirectToRoute('dossiert');
        }

        if ($formsearch->isSubmitted() && $formsearch->isValid()) {
            if (!$searchin->getSearchin()) {
                return $this->redirectToRoute('dossiert');
            }

            $listes = $this->getDoctrine()->getRepository(Dossier::class)->findByRef($searchin);
            $searchin = new SearchIn();
            $formsearch = $this->createform(SearchInType::class, $searchin);

            return $this->render('dossiert/index.html.twig', [
                'listes' => $listes,
                'form' => $form->createView(),
                'formsearch' => $formsearch->createView(),
            ]);
        }

        return $this->render('dossiert/index.html.twig', [
            'listes' => $listes,
            'form' => $form->createView(),
            'formsearch' => $formsearch->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $update = $this->getDoctrine()->getRepository(Dossier::class)->find($id);
        $oldplan = $update->getPlan();

        $form = $this->createForm(UpdateType::class, $update);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ind = $update->getInd();
            if (!isset($ind)) {
                $newfilename =
                    "T" . $update->getNumdossier() . " - " . $update->getRefpiece() . ".pdf";
            } else {
                $newfilename =
                    "T" . $update->getNumdossier() . " - " . $update->getRefpiece() . " - ind " . $ind . ".pdf";
            }
            $directory = "dossier/plan/";
            $file = $form->get('plan')->getData();
            if (isset($file)) {
                if (isset($oldplan)) {
                    $handle = opendir($directory);
                    $oldplan = $directory . $oldplan;
                    unlink($oldplan);
                    closedir($handle);
                }
                $file->move($directory, $newfilename);
                $update->setPlan($newfilename);
            } elseif (isset($oldplan)) {
                $handle = opendir($directory);
                $oldplan = $directory . $oldplan;
                rename($oldplan, $directory . $newfilename);
                closedir($handle);
                $update->setPlan($newfilename);
            }

            $update->setDateupdate(new \DateTime());
            $em->flush();
            $this->addFlash('success', 'Mise à jour reussi');
            return $this->redirectToRoute('dossiert');
        }
        return $this->render('dossiert/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $deldossier = $this->getDoctrine()->getRepository(Dossier::class)->find($id);
        $backdossier = new Backdossier();
        $backdossier->setNumdossier($deldossier->getNumdossier());
        $backdossier->setClient($deldossier->getClient());
        $backdossier->setRefpiece($deldossier->getRefpiece());
        $backdossier->setDesigpiece($deldossier->getDesigpiece());
        $backdossier->setInd($deldossier->getInd());
        $backdossier->setPlan($deldossier->getPlan());
        $backdossier->setDatecreat($deldossier->getDatecreat());
        $backdossier->setDatedelete(new \DateTime());

        $em->remove($deldossier);
        $em->persist($backdossier);
        $em->flush();
        $this->addFlash('success', 'Dossier bien supprimé');

        return $this->redirectToRoute('dossiert');
    }

    /**
     * @Route("/oldfile", name="oldfile")
     */
    public function oldfile(Request $request): Response
    {
        try {
            $file = file("../public/dossier/dossiers-t.txt");
            foreach ($file as $line) {
                $list[] = trim($line);
            }
        } catch (\Throwable $th) {
            $list[] = 'Le fichier "DOSSIERS-T.TXT" est absent !!!';
        }
        $search = new Search;
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chaine = $search->getSearch();
            if (!empty($chaine)) {
                foreach ($list as $line) {
                    if (stristr($line, $chaine)) {
                        $lines[] = $line;
                    }
                }
                if (!isset($lines)) {
                    $lines[] = 'Aucun resultat';
                    $this->addFlash('success', 'Aucun resultat');
                }
                $list = $lines;
                $search = new Search;
                $form = $this->createForm(SearchType::class, $search);
            }
        }
        return $this->render('dossiert/oldfile.html.twig', [
            'lines' => $list,
            'form' => $form->createView(),
        ]);
    }
}
