<?php

namespace App\Controller;

use App\Entity\Mails;
use App\Entity\Famille;
use App\Entity\Contacts;
use App\Entity\TryDebit;
use App\Entity\AppelOffre;
use App\Entity\Fournisseur;
use App\Form\UpdateLigneType;
use App\Services\ServicesApproMat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/appro/", name="appro_")
 * @package APP\Controller\
 * @IsGranted ("ROLE_USER")
 */

class ApproMatController extends AbstractController
{
    protected $services;
    protected $em;
    public function __construct(ServicesApproMat $services, EntityManagerInterface $em)
    {
        $this->services = $services;
        $this->em = $em;
    }

    /**
     * @Route("index", name="index")
     */
    public function index(): Response
    {
        $debit = $this->getDoctrine()->getRepository(AppelOffre::class)->findAo();

        return $this->render('appro_mat/index.html.twig', [
            'lignes' => $debit
        ]);
    }

    /**
     * @Route("statutdebit/{id}", name="statutdebit")
     */
    public function statut($id): Response
    {
        $ligne = $this->getDoctrine()->getRepository(AppelOffre::class)->find($id);
        if ($ligne) {
            $typestatut = $ligne->getStatut();
            if ($typestatut == 'sending') {
                $ligne->setStatut('wait');
            } elseif ($typestatut == 'wait') {
                $ligne->setStatut('sending');
            }
            $this->em->flush();

            return new Response('la ligne ' . $id . ' est selectionner', 200);
        }
        return new Response('proute rate ' . $id, 400);
    }

    /**
     * @Route("recupdebit", name="recupdebit")
     */
    public function recupdebit(): Response
    {
        $param = [
            'dossier' => "dossier/",
            'fichier' => "debit.txt",
            'space' => ";",
            'delfile' => true,
            'size' => 9
        ];
        $debits = $this->services->explosefile($param);

        if (!empty($debits['content'])) {
            foreach ($debits['content'] as $line) {
                $estde = $this->services->selectfamille($line[1]);
                $debit= explode("QTE", $line[2]);
                $linedebit = (new AppelOffre())
                    ->setDatecreat(new \DateTime())
                    ->setClient($line[7])
                    ->setNaf($line[0])
                    ->setMatiere($line[1])
                    ->setDebit($debit[0])
                    ->setEpaisseur(substr($line[1], -1, 1))
                    ->setQuantite(ceil($line[3]))
                    ->setStatut("sending")
                    ->setFamille($estde['famille'])
                    ->setNuance($estde['nuance']);
                $this->em->persist($linedebit);
            }
            $this->em->flush();
        }
        $this->addFlash($debits['type'], $debits['message']);
        return $this->redirectToRoute('appro_index');
    }

    /**
     * @Route("deldebit/{id}", name="deldebit")
     */
    public function deldebit($id): Response
    {
        $supligne = $this->getDoctrine()->getRepository(AppelOffre::class)->find($id);

        if ($supligne === null) {
            $this->addflash('danger', 'Erreur !!! Cette ligne n\'existe pas.');
            return $this->redirectToRoute('appro_mat');
        };
        $this->em->remove($supligne);
        $this->em->flush();
        $this->addFlash('success', 'ligne supprimée');

        return $this->redirectToRoute('appro_index');
    }

    /**
     * @Route("updebit/{id}", name="updebit")
     */
    public function updebit($id, Request $request): Response
    {
        $updligne = $this->getDoctrine()->getRepository(AppelOffre::class)->find($id);

        if ($updligne === null) {
            $this->addflash('danger', 'Erreur !!! Cette ligne n\'existe pas.');
            return $this->redirectToRoute('appro_mat');
        };
        $form = $this->createForm(updateLigneType::class, $updligne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debit = $updligne->getMatiere();
            $estde = $this->services->selectfamille($debit);
            $updligne->setFamille($estde['famille'])
                ->setNuance($estde['nuance']);

            $this->em->flush();
            $this->addFlash('success', 'ligne modifiée');

            return $this->redirectToRoute('appro_index');
        }

        return $this->render("appro_mat/updebit.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("adddebit/{$quit}", name="adddebit")
     */
    public function adddebit(Request $request): Response
    {
        $addligne = new AppelOffre();
        $form = $this->createForm(UpdateLigneType::class, $addligne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debit = $addligne->getMatiere();
            $estde = $this->services->selectfamille($debit);
            $addligne->setFamille($estde['famille'])
                ->setNuance($estde['nuance'])
                ->setDatecreat(new \DateTime())
                ->setStatut('sending');
            $this->em->persist($addligne);
            $this->em->flush();
            $this->addFlash('success', 'ligne ajoutée');

            if (isset($_POST['quit'])) {
                return $this->redirectToRoute('appro_index');
            } else {
                return $this->redirectToRoute('addligne');
            }
        }

        return $this->render('appro_mat/adddebit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("appromail/{send}", name="appromail")
     */
    public function appromail($send = null): Response
    {
        $debit = $this->em->getRepository(AppelOffre::class)->findBy([
            'statut' => 'sending'
        ]);
        if (empty($debit)) {
            $msg = 'Selectionner un ou plusieurs debit(s) avant Merci !!!';
            return $this->render('appro_mat/appromail.html.twig', [
                'msg' => $msg,
                'defaut' => false
            ]);
        }

        function creatTrydebit($value, $fam, $nuan, $fourn)
        {
            $trydebit = new TryDebit();
            $trydebit->setDebits($value);
            $trydebit->setFamille($fam);
            $trydebit->setNuance($nuan);
            foreach ($fourn as $fournsimple) {
                $trydebit->setFournisseurs($fournsimple);
            };
            return $trydebit;
        };
        $nofournisseur = [];
        $notype = [];
        $trydebits = [];
        foreach ($debit as $value) {
            $fam = $value->getFamille();
            $nuan = $value->getNuance();
            if (!$fam || !$nuan) {
                $notype[] = $value;
            } else {
                $fourn = $this->em->getRepository(Fournisseur::class)->findByFamAndNuan($fam->getId(), $nuan->getId());
                if (!$fourn) {
                    $nofournisseur[] = $value;
                } else {
                    if (empty($trydebits)) {
                        $trydebits[] = creatTrydebit($value, $fam, $nuan, $fourn);
                    } else {
                        foreach ($trydebits as $item) {
                            if ($item->getFamille() == $fam && $item->getNuance() == $nuan) {
                                $item->setDebits($value);
                                $add = false;
                                break;
                            } else {
                                $add = true;
                            }
                        }
                        if ($add) {
                            $trydebits[] = creatTrydebit($value, $fam, $nuan, $fourn);
                        };
                    }
                }
            }
        };
        $boucle = [];
        foreach ($trydebits as $debit) {
            $fourns = $debit->getFournisseurs();
            foreach ($fourns as $fourn) {
                if (empty($boucle)) {
                    $boucle[] = $fourn;
                }
                foreach ($boucle as $item) {
                    if ($fourn === $item) {
                        $add = false;
                        break;
                    }
                    $add = true;
                }
                $add ? $boucle[] = $fourn : null;
            }
        }
        $emails = [];
        $nocontact = [];
        foreach ($boucle as $fourn) {
            $email = new Mails();
            $email->setFournisseur($fourn);
            $contacts = $fourn->getContacts();
            if (!$contacts) {
                $nocontact[] = $fourn;
            }
            foreach ($fourn->getContacts() as $contact) {
                $contact->getStatut() == 'OK' ? $email->setAdresmails($contact) : null;
            }
            foreach ($trydebits as $debit) {
                foreach ($debit->getFournisseurs() as $item) {
                    if ($item === $fourn) {
                        foreach ($debit->getDebits() as $deb) {
                            $email->setDebits($deb);
                        }
                        break;
                    }
                }
            }
            $emails[] = $email;
        };
        if (empty($notype) and empty($nofournisseur) and empty($nocontact)) {
            $defaut= false;
        } else {
            $defaut = [
                'notype' => $notype,
                'nofourn' => $nofournisseur,
                'nocontact' => $nocontact,
            ];
        }
    // envoie de mail
        if ($send === "send" && $emails) {
            foreach ($emails as $item) {
                $this->services->sendmail($item);
                foreach ($item->getDebits() as $debit) {
                    $debit->setStatut('send');
                    $debit->setDateupdate(new \DateTime());
                    $this->em->flush();
                };
            }
            $this->addFlash('success', 'Mail envoyer');
            return $this->redirectToRoute('appro_index');
        }

        if (empty($emails)) {
            $msg =  'Aucun mail n\'a put-être généré';
        } else {
            $msg = false;
        };
        return $this->render('appro_mat/appromail.html.twig', [
            'debit' => $emails,
            'msg' => $msg,
            'defaut' => $defaut,
        ]);
    }

    /**
     * @Route("suivimail", name="suivimail")
     */
    public function suivimail(): Response
    {
        $encours = $this->em->getRepository(AppelOffre::class)->findBy([
            'statut' => 'send'
        ]);

        return $this->render('appro_mat/suivimail.html.twig', [
            'lignes' => $encours,
        ]);
    }
}
