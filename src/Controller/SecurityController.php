<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RestpwdType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("restpwd/{slug}", name="restpwd")
     */
    public function restpwd(string $slug, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, Request $request)
    {
        if (isset($slug)) {
            $type = substr($slug, 0, 2);
            $key = substr($slug, 2);

            if ($type === 'id') {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
                $user = $this->getDoctrine()->getRepository(User::class)->find($key);
            } elseif ($type === 'to') {
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                    ['token' => $key]
                );
            };
            if ($user) {
                $form = $this->createForm(RestpwdType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $hash = $encoder->encodePassword($user, $user->getPassword());
                    $user->setPassword($hash);
                    $user->setToken(null);
        
                    $this->em->persist($user);
                    $this->em->flush();
        
                    $this->addFlash('success', 'Mot de passe a bien été changé !!!');
                    return $this->redirectToRoute('index');
                }
                
                return $this->render('security/resetpwd.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        };
        $this->addFlash('danger', 'Cette page n\'existe pas !!!');
        return $this->redirectToRoute('index');
    }
}
