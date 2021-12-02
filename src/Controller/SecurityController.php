<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UpuserType;
use App\Entity\Forgetpwd;
use App\Form\RestpwdType;
use App\Form\ForgetPwdType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        #code...
    }

    /**
     * @Route("/security", name="security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/gestionuser", name="gestionuser")
     */
    public function gestionuser(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $listeuser = $this->getDoctrine()->getRepository(User::class)->findby(
            ['roles' => 'ROLE_USER']
        );
        $listeadmin = $this->getDoctrine()->getRepository(User::class)->findby(
            ['roles' => 'ROLE_ADMIN']
        );

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setDatecreat(new \DateTime());
            $rolesel = $user->getSelectroles();
            if ($rolesel === 'ADMIN') {
                $role = 'ROLE_ADMIN';
            } else {
                $role = 'ROLE_USER';
            };
            $user->setRoles($role);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('gestionuser');
        }

        return $this->render('security/gestionuser.html.twig', [
            'form' => $form->createView(),
            'listeuser' => $listeuser,
            'listeadmin' => $listeadmin
        ]);
    }

    /**
     * @Route("chgtrole/{id}", name="chgtrole")
     */
    public function chgtrole($id, EntityManagerInterface $em)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $nowrole = $user->getRoles();
        $nowrole = $nowrole[0];

        if ($nowrole === 'ROLE_USER') {
            $newrole = "ROLE_ADMIN";
        } else {
            $newrole = "ROLE_USER";
        };
        $user->setRoles($newrole);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('info', 'Role utilisateur modifier');
        return $this->redirectToRoute('gestionuser');
    }

    /**
     * @Route("deluser/{id}", name="deluser")
     */
    public function deluser($id, EntityManagerInterface $em)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Utilisateur supprimer');
        return $this->redirectToRoute('gestionuser');
    }

    /**
     * @Route("/forgetpwd", name="forgetpwd")
     */
    public function mdpoublie(
        Request $request,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer,
        EntityManagerInterface $em
    ) {
        $useremail = new Forgetpwd();
        $form = $this->createForm(ForgetPwdType::class, $useremail);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $useremail->getEmail();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['email' => $email]
            );
            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('restpwd', ['id' => 'to' . $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('admin@cfw.fr')
                    ->to($user->getEmail())
                    ->subject('Mot de passe perdu !!')
                    ->html('<h2> Bonjours ' . $user->getUsername() . '</h2>
                    <p> Cliquez sur le lien suivant pour réinitialiser votre mot de passe </p>
                    <h3>' . $url . '</h3>');
                //$mailer->send($email);

                dd($email);
                $this->addFlash('success', 'Mail bien envoyer');
                return $this->redirectToRoute('index');
            } else {
                $this->addFlash('mailerror', 'L\'adresse d\'email saisie n\'existe pas !!!');
                return $this->redirectToRoute('forgetpwd');
            }
        };

        return $this->render('security/forgetpwd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("restpwd/{id}", name="restpwd")
     */
    public function restpwd(
        string $id,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $em,
        Request $request
    ) {
        $type = substr($id, 0, 2);
        $key = substr($id, 2);

        if ($type === 'id') {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $user = $this->getDoctrine()->getRepository(User::class)->find($key);
            if (!$user) {
                goto error;
            };
            $oldpwd = $user->getPassword();
            $form = $this->createForm(UpuserType::class, $user);
            $mail = true;
            $titre = 'Mise à jour utilisateur';
            $message = 'Mis à jour utilisateur fait !!!';
            $url = 'gestionuser';
        } elseif ($type === 'to') {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['token' => $key]
            );
            if (!$user) {
                goto error;
            };
            $form = $this->createForm(RestpwdType::class, $user);
            $mail = false;
            $titre = "Changement mot de passe";
            $message = "Mot de passe a bien été changé !!!";
            $url = 'index';
        } else {
            goto error;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pwd = $user->getPassword();
            if ($pwd !== "empty") {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $user->setToken(null);
            } else {
                $user->setPassword($oldpwd);
            };

            // $em->persist($user);
            // $em->flush();
            $this->addFlash('success', $message);
            return $this->redirectToRoute($url);
        }

        return $this->render('security/resetpwd.html.twig', [
            'titre' => $titre,
            'mail' => $mail,
            'user' => $user,
            'form' => $form->createView()
        ]);

        error:
        $this->addFlash('danger', 'Cette page n\'existe pas !!!');
        return $this->redirectToRoute('index');
    }
}
