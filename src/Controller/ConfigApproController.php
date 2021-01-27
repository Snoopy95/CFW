<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\Famille;
use App\Entity\Fournisseur;
use App\Entity\Nuance;
use App\Form\ContactType;
use App\Form\FournisseurType;
use App\Services\ServicesApproMat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/config/", name="config_")
 */
class ConfigApproController extends AbstractController
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
        return $this->render('config_appro/index.html.twig', [
            'liste' => '',
        ]);
    }

    /**
     * @Route("familles", name="familles")
     */
    public function familles(): Response
    {
        $liste = $this->em->getRepository(Famille::class)->findAll();

        return $this->render('config_appro/familles.html.twig', [
            'liste' => $liste,
        ]);
    }

    /**
     * @Route("statutfamille/{id}", name="statutfamille")
     */
    public function statutfamille($id): Response
    {
        $famille = $this->em->getRepository(Famille::class)->find($id);
        if ($famille) {
            $typestatut = $famille->getStatutfamille();
            if ($typestatut == 'OK') {
                $famille->setStatutfamille('NOK');
            } elseif ($typestatut == 'NOK') {
                $famille->setStatutfamille('OK');
            }
            $this->em->flush();
            return new Response('Statut changer ' . $id, 200);
        }
        return new Response('Erreur de l\'ID', 400);
    }

    /**
     * @Route("nuances", name="nuances")
     */
    public function nuances(): Response
    {
        $liste = $this->em->getRepository(Nuance::class)->findAll();

        return $this->render('config_appro/nuances.html.twig', [
            'liste' => $liste,
        ]);
    }

    /**
     * @Route("statutnuance/{id}", name="statutnuance")
     */
    public function statutnuance($id): Response
    {
        $nuance = $this->em->getRepository(Nuance::class)->find($id);
        if ($nuance) {
            $typestatut = $nuance->getStatutnuance();
            if ($typestatut == 'OK') {
                $nuance->setStatutnuance('NOK');
            } elseif ($typestatut == 'NOK') {
                $nuance->setStatutnuance('OK');
            }
            $this->em->flush();
            return new Response('Statut changer ' . $id, 200);
        }
        return new Response('Erreur de l\'ID', 400);
    }

    /**
     * @Route("fournisseurs", name="fournisseurs")
     */
    public function fournisseurs(): Response
    {
        $liste = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();

        return $this->render('config_appro/fournisseurs.html.twig', [
            'liste' => $liste,
        ]);
    }

    /**
     * @Route("fichefournisseur/{id}", name="fichefournisseur")
     */
    public function fichefournisseur($id): Response
    {
        $fiche= $this->em->getRepository(Fournisseur::class)->find($id);
        $familles = $this->em->getRepository(Famille::class)->findBy(
            ['statutfamille' => 'OK']
        );
        $nuances = $this->em->getRepository(Nuance::class)->findBy(
            ['statutnuance' => 'OK']
        );

        return $this->render("config_appro/fichefournisseur.html.twig", [
            'fiche' => $fiche,
            'familles' => $familles,
            'nuances' => $nuances,
        ]);
    }

    /**
     * @Route("statutfournisseur/{id}", name="statutfournisseur")
     */
    public function statutfournisseur($id): Response
    {
        $fourn = $this->em->getRepository(Fournisseur::class)->find($id);
        if ($fourn) {
            $typestatut = $fourn->getStatut();
            if ($typestatut == 'OK') {
                $fourn->setStatut('NOK');
            } elseif ($typestatut == 'NOK') {
                $fourn->setStatut('OK');
            }
            $this->em->flush();
            return new Response('Statut changer ' . $id, 200);
        }
        return new Response('Erreur de l\'ID', 400);
    }

    /**
     * @Route("addfournisseur", name="addfournisseur")
     */
    public function addfournisseur(Request $request): Response
    {
        $addfourn = new Fournisseur();
        $addcontact = new Contacts();
        $formfourn = $this->createForm(FournisseurType::class, $addfourn);
        $formcontact = $this->createForm(ContactType::class, $addcontact);
        $formfourn->handleRequest($request);
        $formcontact->handleRequest($request);

        if ($formfourn->isSubmitted() && $formfourn->isValid()) {
            $addfourn->setStatut('OK');
            $msg = "";
            $nom = $addcontact->getNom();
            $email = $addcontact->getMail();

            if ($nom && $email) {
                $addcontact->setCodecont($addfourn->getCodefour());
                $addcontact->setStatut('OK');
                $addcontact->setFournisseur($addfourn);
                $msg = " et contact";
                $this->em->persist($addcontact);
            }
            $this->em->persist($addfourn);
            $this->em->flush();
            $this->addFlash('success', 'Fournisseur' . $msg . ' bien ajouter');

            return $this->redirectToRoute('config_fournisseurs');
        }
        return $this->render("config_appro/addfournisseur.html.twig", [
            'formfourn' => $formfourn->createView(),
            'formcontact' => $formcontact->createView(),
        ]);
    }

    /**
     * @Route("delfournisseurs", name="delfournisseurs")
     */
    public function delfournisseurs(Request $request): Response
    {
        $list = json_decode($request->getContent(), true);
        if (!empty($list)) {
            foreach ($list as $id) {
                $fourn = $this->em->getRepository(Fournisseur::class)->find($id);
                $contacts = $this->em->getRepository(Contacts::class)->findBy(
                    ['fournisseur' => $id]
                );
                foreach ($contacts as $contact) {
                    $fourn->removeContact($contact);
                }
                $this->em->remove($fourn);
            }
            $this->em->flush();
            $this->addFlash('success', 'Liste fournisseur supprimer');
            return new Response('liste de fournisseur supprimer', 200);
        }
        return new Response('Erreur de data', 400);
    }

    /**
     * @Route("updatefournisseur", name="updatefournisseur")
     */
    public function updatefournisseur(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!empty($data)) {
            $update = false;
            $fourn= $this->em->getRepository(Fournisseur::class)->find($data['id']);
            if (isset($data['name'])) {
                $fourn->setNom($data['name']);
                $update = true;
            }
            if (isset($data['code'])) {
                $fourn->setCodefour($data['code']);
                $update = true;
            }
            if ($update) {
                $this->em->flush();
            }
            return new Response('Update faite', 200);
        }
        return new Response("Erreur de Date", 400);
    }

    /**
     * @Route("contacts", name="contacts")
     */
    public function contacts(): Response
    {
        $contacts = $this->em->getRepository(Contacts::class)->findBy(
            [
                'fournisseur' => null,
            ]
        );

        return $this->render("config_appro/contacts.html.twig", [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("addcontact", name="addcontact")
     */
    public function addcontact(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!empty($data)) {
            $fourn = $this->em->getRepository(Fournisseur::class)->find($data['id']);
            $addcontact = (new Contacts())
                ->setNom($data['name'])
                ->setMail($data['mail'])
                ->setCodecont($fourn->getCodefour())
                ->setStatut('OK')
                ->setFournisseur($fourn);

            $this->em->persist($addcontact);
            $this->em->flush();
            $this->addFlash('success', 'Contatct ajouter');
            return new Response('Contact ajouter', 200);
        }
        return new Response('Erreur de data', 400);
    }

    /**
     * @Route("delcontact", name="delcontact")
     */
    public function delcontact(Request $request): Response
    {
        $list = json_decode($request->getContent(), true);
        if (!empty($list)) {
            foreach ($list as $id) {
                $contact = $this->em->getRepository(Contacts::class)->find($id);
                $this->em->remove($contact);
                $this->em->flush();
            }
            return new Response('liste de contact supprimer', 200);
        }
        return new Response('Erreur de data', 400);
    }

    /**
     * @Route("updatecontact", name="updatecontact")
     */
    public function updatecontact(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (!empty($data)) {
            $contact= $this->em->getRepository(Contacts::class)->find($data['id']);
            $contact->setMail($data['mail'])
                ->setNom($data['name']);
            $this->em->flush();
            $this->addFlash('success', 'Contact mise à jour');
            return new Response('Contact mise à jour', 200);
        }
        return new Response('Erreur de data', 400);
    }

    /**
     * @Route("statutcontact/{id}", name="statutcontact")
     */
    public function statutcontact($id): Response
    {
        $contact = $this->em->getRepository(Contacts::class)->find($id);
        if ($contact) {
            $typestatut = $contact->getStatut();
            if ($typestatut == 'OK') {
                $contact->setStatut('NOK');
            } elseif ($typestatut == 'NOK') {
                $contact->setStatut('OK');
            }
            $this->em->flush();
            return new Response('Statut changer ' . $id, 200);
        }
        return new Response('Erreur de l\'ID', 400);
    }

    /**
     * @Route("recupfournisseur", name="recupfournisseur")
     */
    public function recupfournisseur(): Response
    {
        $param = [
            'dossier' => "dossier/structure/",
            'fichier' => "fourni.txt",
            'space' => "\t",
            'delfile' => true,
            'size' => 2,
        ];
        $fournisseurs = $this->services->explosefile($param);

        $param = [
            'dossier' => "dossier/structure/",
            'fichier' => "contacts.txt",
            'space' => "\t",
            'delfile' => true,
            'size' => 4,
        ];
        $contacts = $this->services->explosefile($param);

        if ($fournisseurs['content']) {
            foreach ($fournisseurs['content'] as $line) {
                $linefournisseur = (new Fournisseur())
                    ->setCodefour($line[0])
                    ->setNom($line[1])
                    ->setStatut("OK");
                $this->em->persist($linefournisseur);
            }
            $this->em->flush();
        }
        if ($contacts['content']) {
            foreach ($contacts['content'] as $line) {
                $linecontact = (new Contacts())
                    ->setNom($line[1])
                    ->setMail(strtolower($line[2]))
                    ->setCodecont($line[3])
                    ->setFournisseur(
                    $this->services->selectfourn($line[3])
                )
                    ->setStatut('OK');
                $this->em->persist($linecontact);
            }
            $this->em->flush();
        }
        $this->addFlash($fournisseurs['type'], $fournisseurs['message']);
        $this->addFlash($contacts['type'], $contacts['message']);
        return $this->redirectToRoute('config_fournisseurs');
    }

    /**
     * @Route("recupfamille", name="recupfamille")
     */
    public function recupfamille(): Response
    {
        $param = [
            'dossier' => "dossier/structure/",
            'fichier' => "fami.txt",
            'space' => "\t",
            'delfile' => true,
            'size' => 2,
        ];
        $famille = $this->services->explosefile($param);
        if ($famille['content']) {
            foreach ($famille['content'] as $line) {
                $linefamille = (new Famille())
                    ->setNomfamille($line[0])
                    ->setCodefamille($line[1])
                    ->setStatutfamille('OK')
                    ->setDatecreat(new \DateTime());
                $this->em->persist($linefamille);
            }
            $this->em->flush();
        }
        $this->addFlash($famille['type'], $famille['message']);
        return $this->redirectToRoute('config_familles');
    }

    /**
     * @Route("recupnuance", name="recupnuance")
     */
    public function recupnuance(): Response
    {
        $param = [
            'dossier' => "dossier/structure/",
            'fichier' => "param.txt",
            'space' => "\t",
            'delfile' => true,
            'size' => 4,
        ];
        $nuance = $this->services->explosefile($param);
        if ($nuance['content']) {
            foreach ($nuance['content'] as $line) {
                $linenuance = (new Nuance())
                    ->setCodenuance($line[3])
                    ->setName($line[1])
                    ->setDatecreat(new \DateTime())
                    ->setStatutnuance('OK');
                $this->em->persist($linenuance);
            }
            $this->em->flush();
        }
        $this->addFlash($nuance['type'], $nuance['message']);
        return $this->redirectToRoute('config_nuances');
    }

    /**
     * @Route("selectfamille", name="selectfamille")
     */
    public function selectfamille(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!empty($data)) {
            $fourn = $this->em->getRepository(Fournisseur::class)->find($data['idfourn']);
            $famille = $this->em->getRepository(Famille::class)->find($data['idfamille']);
            $familles = $fourn->getFamilles();
            $ok= false;
            foreach ($familles as $values) {
                $famille == $values? $ok= true: null;
            }
            $ok? $fourn->removeFamille($famille):$fourn->addFamille($famille);
            $this->em->flush();
            return new Response('tout va bien', 200);
        }
        return new Response('Erreur de data', 400);
    }

    /**
     * @Route("selectnuance", name="selectnuance")
     */
    public function selectnuance(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!empty($data)) {
            $fourn = $this->em->getRepository(Fournisseur::class)->find($data['idfourn']);
            $nuance = $this->em->getRepository(Nuance::class)->find($data['idnuance']);
            $nuances = $fourn->getNuances();
            $ok = false;
            foreach ($nuances as $values) {
                $nuance == $values ? $ok = true : null;
            }
            $ok ? $fourn->removeNuance($nuance) : $fourn->addNuance($nuance);
            $this->em->flush();
            return new Response('tout va bien', 200);
        }
        return new Response('Erreur de data', 400);
    }
}
