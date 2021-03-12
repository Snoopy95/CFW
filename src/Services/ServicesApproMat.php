<?php

namespace App\Services;

use App\Entity\Nuance;
use App\Entity\Famille;
use App\Entity\Fournisseur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ServicesApproMat
{
    protected $em;

    public function __construct(EntityManagerInterface $em, MailerInterface $mail)
    {
        $this->em = $em;
        $this->mail = $mail;
    }

    public function selectfamille(string $type)
    {
        $estde = explode('*', $type);
        $famille = $this->em->getRepository(Famille::class)->findOneBy(
            ['codefamille' => $estde[0]]
        );
        if (!$famille) {
            $famille = null;
        };
        $nuance = $this->em->getRepository(Nuance::class)->findOneBy(
            ["codenuance" => $estde[1]]
        );
        if (!$nuance) {
            $nuance = null;
        }
        $result = [
            'famille' => $famille,
            'nuance'  => $nuance
        ];
        return $result;
    }

    /**
     * decoupage de fichier
     *
     * @param [type] Array (dossier =nom du dossier, fichier = nom du fichier,
     * space =  caractere de coupage, true/false = RAZ du fichier, size = taille attendu)
     * @return void
     * content = Array du content, type = type de message, message = meesage
     */
    public function explosefile(array $fileparametre)
    {
        try {
            $dossier = $fileparametre['dossier'];
            $fichier = $fileparametre['fichier'];
            $handle = opendir($dossier);
            $filebrut = file($dossier . $fichier);

            if ($filebrut) {
                if (count($filebrut) > 1) {
                    $file = $filebrut;
                } else {
                    $file = explode("\r", $filebrut[0]);
                }
                foreach ($file as $line) {
                    $line = htmlentities(trim($line));
                    $sousline = explode($fileparametre['space'], $line);
                    if (count($sousline) == $fileparametre['size']) {
                        $cleanline = [];
                        foreach ($sousline as $text) {
                            $cleanline[] = trim($text);
                        };
                        $content[] =  $cleanline;
                    }
                }
                $message = "import du fichier \"" . $fichier . "\" reussi";
                $type = "success";
            } else {
                $content = [];
                $message = "Le fichier \"" . $fichier . "\" est vide !!!";
                $type = "danger";
            }
            if ($fileparametre['delfile'] == true) {
                unlink($dossier . $fichier);
                fopen($dossier . $fichier, 'w');
            }
            closedir($handle);
        } catch (\Throwable $th) {
            $content = [];
            $message = "Erreur Fatale !!!! ";
            $type = "danger";
        }
        return [
            'content' => $content,
            'type' => $type,
            'message' => $message,
        ];
    }

    public function selectfourn(int $codecontact)
    {
        $fourn = $this->em->getRepository(Fournisseur::class)->findOneBy(
            ['codefour' => $codecontact]
        );
        if (!$fourn) {
            $fourn = null;
        };
        return $fourn;
    }

    public function sendmail($item = null)
    {
        if ($item === null) {
            dd('pas de mail');
        } else {
            // dd($item);
            foreach ($item->getAdressmails() as $contact) {
                $email = (new TemplatedEmail())
                    ->from('ACHAT@CFW.fr')
                    ->to('boxalacon@gmail.com')
                    ->subject('Demande d\'offre de prix')
                    ->htmlTemplate('Emails/Appeldoffre.html.twig')
                    ->context([
                        'nom' => $contact->getNom(),
                        'debits' => $item->getDebits(),
                    ]);
                $this->mail->send($email);
            }
        }
    }
}
