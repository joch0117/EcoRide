<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilUserType;
use App\Services\ProfileService;
use App\Services\DashboardService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

final class DashboardController extends AbstractController
{
    #[Route('/espace-utilisateur', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(DashboardService $dashboardService ): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $average = $user->getAverageRating();


        if (!$user->isProfilComplet()) {
            return $this->redirectToRoute('app_dashboard_profil');
        }

        $data = $dashboardService->getDashboardData($user);
        
        return $this->render('dashboard/espace-utilisateur.html.twig',
    [
        'user'=>$user,
        'average'=>$average,
        'vehicles'=> $data['vehicles'],
    ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/espace-utilisateur/profil', name:'app_dashboard_profil')]
    public function editProfile(
        Request $request,
        EntityManagerInterface $em,
        ProfileService $profileService,
        SluggerInterface $slugger
    ): Response{

        $user = $this->getUser();
        if(!$user instanceof User){
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page .');
        }
        
        $form =$this->createForm(EditProfilUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            if(!$profileService->validateDriverAge($user,$form)){
                $form->get('is_driver')->addError(new FormError('Vous devez avoir au moins 18 ans pour être chauffeur.'));
                return $this->render('dashboard/profil.html.twig',[
                    'form'=>$form->createView(),
                ]);
            }

            $file =$form->get('photo_url')->getData();

            if($file){
                try{
                    $profileService->handleProfilePhoto($file,$user,$slugger);
                }catch(\Exception $e){
                    $form->get('photo')->addError(new FormError($e->getMessage()));
                    return $this->render('dashboard/profil.html.twig',[
                        'form'=>$form->createView(),
                    ]);
                }
            }else{
                $profileService->setDefaultPhoto($user);
            }

            $em->flush();

            if ($user->isDriver() && count($user->getVehicles()) === 0) {
                    $this->addFlash('warning', 'Ajoutez un véhicule pour pouvoir proposer un trajet.');
                    return $this->redirectToRoute('app_vehicle_add');
            }


            $this->addFlash('success','Profil mis à jour avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/profil.html.twig',[
        'form'=>$form->createView(),
        ]);
    }
}
