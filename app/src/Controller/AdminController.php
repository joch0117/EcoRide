<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateEmployeType;
use App\Service\AdminService;
use App\Service\StatCollectorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin', name: 'admin_')]
final class AdminController extends AbstractController
{
    #[Route('', name: 'admin')]
    public function dashboard(AdminService $adminService): Response
    {
        $directData= $adminService->dataPlateform();

        return $this->render('admin/dashboard.html.twig',
    [
            'directData'=>$directData,
    ]
    );
        //methode mongodb,[
         //   'lastSnapshot'=> $adminService->getLastSnapshot()
        //]);
    }

    #[Route('/stats/generate', name: 'admin_stats_generate', methods: ['POST'])]
    public function generateStats(Request $request, StatCollectorService $statCollector): RedirectResponse
    {
    if (!$this->isCsrfTokenValid('generate_stats', $request->request->get('_token'))) {
        throw $this->createAccessDeniedException('CSRF token invalide.');
    }

    $statCollector->collect();
    $this->addFlash('success', 'Statistiques du jour générées avec succès.');
    return $this->redirectToRoute('admin_admin');
    }


    #[Route('/create', name: 'admin_create')]
    public function createEmploye(Request $request, AdminService $adminService): Response
    {
        $user = new User();
        $form=$this->createForm(CreateEmployeType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $plainPassword = $form->get('plainPassword')->getData();
            $adminService->createEmploye($plainPassword,$user);

            $this->addFlash('succes','Employé créé avec succès');
            return $this->redirectToRoute('admin_admin');
        }
        return $this->render('admin/create.html.twig',[
            'form' => $form->createView()
        ]);
    }


    #[Route('/gestion', name: 'admin_users')]
    public function gestionUser(Request $request,AdminService $adminService): Response
    {
        $search = $request->query->get('search');

        $groupedUsers= $search 
        ? $adminService->searchUsers($search)
        : $adminService->getUserGrouped();


        
        return $this->render('admin/gestion.html.twig',[
            'employees'=> $groupedUsers['employees'],
            'users'=> $groupedUsers['users'],
            'search'=> $search
        ]);
    }

    #[Route('/user/{id}/toggle', name: 'admin_toggle_user', methods: ['POST'])]
    public function toggleUser(User $user, AdminService $adminService,Request $request): Response
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('toggle-user-' . $user->getId(), $token)) {
        throw $this->createAccessDeniedException('Token CSRF invalide');
        }
        $adminService->toggleSuspension($user);
        return $this->redirectToRoute('admin_admin_users');
    }

    #[Route('/stats/data', name: 'stats_data')]
    public function statsData(AdminService $adminService): JsonResponse
    {
        return new JsonResponse([
            'trajets' => $adminService->getTrajetsRealisesParJour(),
            'credits' => $adminService->getCreditsGagnesParJour()
        ]);
    }
}
