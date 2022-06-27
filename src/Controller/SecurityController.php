<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Quote;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Util\GamificationEngine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        $users = $em->getRepository(User::class)->findAll();
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'categories' => $categories, 'users' => $users]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, MailerInterface $mailer)
    {
        $categories = $em->getRepository(Category::class)->findAll();
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($user->getPassword());
            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new Email())
                ->from('my-app@example.com')
                ->to("{$user->getEmail()}")
                ->subject('Bienvenue sur la quote machine !')
                ->text("Bienvenue {$user->getName()} ! Merci d'avoir rejoint la quote machine. À bientôt")
                ->html("<h1>Bienvenue {$user->getName()} !<br/>Merci d'avoir rejoint la quote machine.<br/> À bientôt</h1>");

            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'controller_name' => 'SecurityController',
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(EntityManagerInterface $em, GamificationEngine $gameEngine): Response
    {
        $quotes = $em->getRepository(Quote::class)->findAll();
        $categories = $em->getRepository(Category::class)->findAll();
        $level = $gameEngine->computeLevelForUser($this->getUser());
        $nextLevel = $gameEngine->computeExperienceNeededForLevel($level);
        $xpForCurrentLevel = $gameEngine->computeExperienceNeededForLevel($level - 1);

        return $this->render('security/profile.html.twig', ['categories' => $categories, 'level' => $level, 'nextLevel' => $nextLevel, 'xpCurrentLevel' => $xpForCurrentLevel]);
    }
}
