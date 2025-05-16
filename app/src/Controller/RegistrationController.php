<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
use App\Service\PreferenceService;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, Security $security,PreferenceService $pService): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie email existant
            if ($em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()])) {
                $form->get('email')->addError(new FormError('Un compte existe déjà avec cet email.'));
            } else {
                $plainPassword = $form->get('plainPassword')->getData();

                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
                $user->setRoles(['ROLE_USER']);

                try {
                    $em->persist($user);
                    //mise à jour des préférence imposé par la plateforme
                    $pService->createUserWithDefaultPreferences($user);
                    $em->flush();

                    $this->addFlash('success', 'Bienvenue ! Votre compte a bien été créé ');

                    $security->login($user, LoginAuthenticator::class);

                    return $this->redirectToRoute('app_home');
                } catch (\Throwable $e) {
                    $this->addFlash('danger', 'Erreur interne : inscription échouée.');
                    return $this->redirectToRoute('app_registration');
                }
            }
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
