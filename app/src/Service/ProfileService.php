<?php

namespace App\Service;


use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileService
{
    public function __construct(
        private string $uploadDir = 'public/uploads/users'
    ){}
    //vérif age conducteur
    public function validateDriverAge(User $user, FormInterface $form):bool{

        $toBeDriver = $form->get('is_driver')->getData();

        if(!$toBeDriver){
            return true;
        }

        $birthdate = $user->getDatebirth();
        if(!$birthdate instanceof \DateTimeInterface){
            return false;
        }

        $age=$birthdate ->diff(new \DateTime())->y;

        return $age >= 18;
    }
    //vérif format photo + enregistrement photo dans le dossier uploads
    public function handleProfilePhoto(UploadedFile $file , User $user , SluggerInterface $slugger):void
    {
        $mime = $file->getMimeType();
        $allowed = ['image/jpeg','image/png','image/webp'];

        if (!in_array($mime,$allowed)){
            throw new \Exception('Format de fichier non autorisé .Format acceptés : jpg , png , webp.');
        }
    file_put_contents('/tmp/profile_service.log', "User: " . get_current_user() . "\n", FILE_APPEND);
        $originalFileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFileName);
        $extension=$file->guessExtension();
        $newFilename =$safeFilename . '-' . uniqid() . '.'.$extension;
        
        if (!is_dir($this->uploadDir)) {
    file_put_contents('/tmp/profile_service.log', "DOSSIER MANQUANT : $this->uploadDir\n", FILE_APPEND);
    } else {
    file_put_contents('/tmp/profile_service.log', "DOSSIER OK : $this->uploadDir\n", FILE_APPEND);
    }

    if (!is_dir($this->uploadDir)) {
    mkdir($this->uploadDir, 0775, true);
    file_put_contents('/tmp/profile_service.log', "Dossier créé par PHP : $this->uploadDir\n", FILE_APPEND);
}

    file_put_contents('/tmp/profile_service.log', "IS_DIR: " . (is_dir($this->uploadDir) ? 'OK' : 'KO') . "\n", FILE_APPEND);
    file_put_contents('/tmp/profile_service.log', "PERMS: " . substr(sprintf('%o', fileperms($this->uploadDir)), -4) . "\n", FILE_APPEND);
    file_put_contents('/tmp/profile_service.log', "WHOAMI: " . get_current_user() . "\n", FILE_APPEND);
    file_put_contents($this->uploadDir . '/testwrite.txt', "hello!");



        $file->move(
            $this->uploadDir,
            $newFilename
        );

        $user->setPhotoUrl('uploads/users/' . $newFilename);
    }
    
    //mise en place d'une image par defaut
    public function setDefaultPhoto(User $user):void
    {
        if (!$user->getPhotoUrl()){
            $user->setPhotoUrl('default_profil.png');
        }
    }


}