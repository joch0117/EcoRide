<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(HttpFoundationRequest $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em,Security $security): Response
    {
        if($request->isMethod('POST')){
            $pseudo=$request->request->get('pseudo');
            $email=$request->request->get('email');
            $password=$request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');
            if($password !== $confirmPassword){
                $this->addFlash('danger','Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_registration');
            }
            if(!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?]).{8,}$/', $password)){
                $this->addFlash('danger','Le mot de passe doit contenir au minimum 8 carractére , une majuscule et un chiffre et un carractére spécial.');
                return $this->redirectToRoute('app_registration');
            }
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($existingUser) {
                $this->addFlash('danger', 'Un compte existe déjà avec cet email.');
                return $this->redirectToRoute('app_registration');
            }
            try{
            $user = new User();
            $user->setUsername($pseudo);
            $user->setEmail($email);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $user->setCredit(20);
            $em->persist($user);
            $em->flush();
            $security->login($user);
            $referer = $request->headers->get('referer') ?? $this->generateUrl('app_home');
            return $this->redirect($referer);
            }catch(\Exception $e){
                $this->addFlash('danger', 'Une erreur est survenue lors de votre inscription.');
                return $this->redirectToRoute('app_registration');
            }
        }
        return $this->render('registration/index.html.twig');
    }
}
