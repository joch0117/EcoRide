<?php

namespace App\Controller;

use Symfony\Component\Form\FormError;
use DateTime;
use App\Entity\User;
use App\Form\EditProfilUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
final class UserProfileController extends AbstractController
{
    #[Route('/profil-utilisateur', name: 'app_user_profile')]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        /** @var User $user */
        $user = $this->getUser();
    
        $form = $this->createForm(EditProfilUserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var UploadedFile|null $photoFile */
                $photoFile = $form->get('photo_url')->getData();

                //vérification de l'age pour être chauffeur
                if ($user->isDriver()){
                    $birthDate = $user->getDatebirth();
                    if ($birthDate){
                        $now= new DateTime();
                        $age=$now->diff($birthDate)->y;

                        if ($age<18){
                            $form->get('is_driver')->addError(new FormError('Vous devez avoir au moin 18 ans '));
                            return $this->render('user_profile/index.html.twig',[
                                'form'=>$form->createView(),
                                'user'=>$user,
                            ]);
                        }
                    }
                }
                if ($photoFile) {
                    // Vérifier le type MIME
                    $mimeType = $photoFile->getMimeType();
                    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) {
                        $this->addFlash('danger', 'Format de fichier non autorisé. Utilise JPG, PNG ou WebP.');
                        return $this->redirectToRoute('app_user_profile');
                    }
    
                    // Nom de fichier sécurisé
                    $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();
    
                    // Tentative de déplacement du fichier
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
    
                    $user->setPhotoUrl('uploads/users/' . $newFilename);
                }
                //définition  photo de profil par defaut
                if(!$user->getPhotoUrl()){
                    $user->setPhotoUrl('uploads/users/default_profil.png');
                }
                $em->flush();
                $this->addFlash('success', 'Profil mis à jour avec succès.');
                return $this->redirectToRoute('app_dashboard');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de la mise à jour du profil.');
            }
        }
    
        return $this->render('user_profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}

