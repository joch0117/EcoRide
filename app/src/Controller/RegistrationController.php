<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
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
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, Security $security): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['password'] !== $data['confirmPassword']) {
                $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            } else {
                $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
                if ($existingUser) {
                    $form->get('email')->addError(new FormError('Un compte existe déjà avec cet email.'));
                } else {
                    try {
                        $user = new User();
                        $user->setUsername($data['pseudo']);
                        $user->setEmail($data['email']);
                        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
                        $user->setPassword($hashedPassword);

                        $em->persist($user);
                        $em->flush();

                        $security->login($user, LoginAuthenticator::class);

                        return $this->redirectToRoute('app_home');
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                        $this->addFlash('danger', 'Une erreur est survenue lors de votre inscription.');
                        return $this->redirectToRoute('app_registration');
                    }
                }
            }
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
