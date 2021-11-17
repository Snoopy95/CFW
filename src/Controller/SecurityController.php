<?php

namespace App\Controller;

use App\Entity\User;
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
        $listeuser = $this->getDoctrine()->getRepository(User::class)->findAll();
        $listeadmin = $this->getDoctrine()->getRepository(User::class)->findby(
            ['roles' => 'ROLE_ADMIN']
        );

        dd($listeuser);
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setDatecreat(new \DateTime());
            $rolesel = $user->getSelectroles();
            if ($rolesel === 'ADMIN') {
                $role[] = 'ROLE_ADMIN';
            } else {
                $role[] = 'ROLE_USER';
            };
            $user->setRoles($role);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('security/gestionuser.html.twig', [
            'form' => $form->createView(),
            'listeadmin' => $listeadmin
        ]);
    }
}
