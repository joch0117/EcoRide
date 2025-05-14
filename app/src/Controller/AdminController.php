<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateEmployeType;
use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin')]
final class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }


    #[Route('/employe', name: 'app_admin_create')]
    public function createEmploye(Request $request, AdminService $adminService): Response
    {
        $user = new User();
        $form=$this->createForm(CreateEmployeType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $plainPassword = $form->get('plainPassword')->getData();
            $adminService->createEmploye($plainPassword,$user);

            $this->addFlash('succes','Employé créé avec succès');
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('admin/create.html.twig');
    }


    #[Route('/gestion', name: 'app_admin')]
    public function gestionUser(): Response
    {
        return $this->render('admin/gestion.html.twig');
    }

}
