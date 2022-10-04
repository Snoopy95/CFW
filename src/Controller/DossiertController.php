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
            $name = $adddossier->getRefpiece();
            $newname = str_replace("/", "-", $name);
            if (empty($ind)) {
                $newfilename =
                    "T{$adddossier->getNumdossier()} - {$newname}";
            } else {
                $newfilename =
                    "T{$adddossier->getNumdossier()} - {$newname} - {$ind}";
            }
            $directoryplan = $this->getParameter('upload_plan_dossier');
            $directorystep = $this->getParameter('upload_3d_dossier');
            $plan = $form->get('plan')->getData();
            $step = $form->get('step')->getData();
            if (isset($plan)) {
                $plan->move($directoryplan, $newfilename.".pdf");
                $adddossier->setPlan($newfilename.".pdf");
            }
            if (isset($step)) {
                $step->move($directorystep, $newfilename.".step");
                $adddossier->setStep($newfilename.".step");
            }
            $em->persist($adddossier);
            $em->flush();
            $this->addFlash('success', 'Dossier bien enregistré');
            $this->addFlash('print', $directoryplan.$newfilename.'.PDF');
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
        $oldstep = $update->getStep();

        $form = $this->createForm(UpdateType::class, $update);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ind = $update->getInd();
            $name = $update->getRefpiece();
            $newname = str_replace("/", "-", $name);
            if (!isset($ind)) {
                $newfilename =
                    "T{$update->getNumdossier()} - {$newname}";
            } else {
                $newfilename =
                    "T{$update->getNumdossier()} - {$newname} - {$ind}";
            }
            $directoryplan = $this->getParameter('upload_plan_dossier');
            $directorystep = $this->getParameter('upload_3d_dossier');
            $plan = $form->get('plan')->getData();
            $step = $form->get('step')->getData();
            if (isset($plan)) {
                if (isset($oldplan)) {
                    $handle = opendir($directoryplan);
                    $oldplan = $directoryplan . $oldplan;
                    unlink($oldplan);
                    closedir($handle);
                }
                $plan->move($directoryplan, $newfilename.".pdf");
                $update->setPlan($newfilename.".pdf");
            } elseif (isset($oldplan)) {
                $handle = opendir($directoryplan);
                $oldplan = $directoryplan . $oldplan;
                rename($oldplan, $directoryplan . $newfilename.".pdf");
                closedir($handle);
                $update->setPlan($newfilename.".pdf");
            }
            if (isset($step)) {
                if (isset($oldstep)) {
                    $handle = opendir($directorystep);
                    $oldstep = $directorystep . $oldstep;
                    unlink($oldstep);
                    closedir($handle);
                }
                $step->move($directorystep, $newfilename.".step");
                $update->setStep($newfilename.".step");
            } elseif (isset($oldstep)) {
                $handle = opendir($directorystep);
                $oldstep = $directorystep . $oldstep;
                rename($oldstep, $directorystep . $newfilename.".step");
                closedir($handle);
                $update->setStep($newfilename.".step");
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
        $backdossier = (new Backdossier())
            ->setNumdossier($deldossier->getNumdossier())
            ->setClient($deldossier->getClient())
            ->setRefpiece($deldossier->getRefpiece())
            ->setDesigpiece($deldossier->getDesigpiece())
            ->setInd($deldossier->getInd())
            ->setPlan($deldossier->getPlan())
            ->setStep($deldossier->getStep())
            ->setDatecreat($deldossier->getDatecreat())
            ->setDatedelete(new \DateTime());
        // $plan = $deldossier->getPlan();
        // $directory = "dossier/plan/";
        // if (!empty($plan)) {
        //     $handle = opendir($directory);
        //             $plan = $directory . $plan;
        //             unlink($plan);
        //             closedir($handle);
        // }
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
