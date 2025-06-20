<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;

final class PhotoController extends AbstractController
{
    #[Route('/photo/{id}', name: 'app_photo')]
    public function profilePhoto(User $user): BinaryFileResponse
    {
        $filename = $user->getPhotoUrl() ?? 'default.png';
        $uploadDir = $this->getParameter('photo_directory');
        $path = rtrim($uploadDir . '/' ). '/' . $filename;

        if (!file_exists($path)) {
            $path = $this->getParameter('kernel.project_dir') . '/public/images/default.png';
        }
        return new BinaryFileResponse($path);
    }
}

