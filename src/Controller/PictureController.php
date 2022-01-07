<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Picture;
use App\Service\File\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/picture')]
class PictureController extends AbstractController
{
//    #[Route('/upload', name: 'picture_upload', methods: ['POST'])]
//    #[IsGranted("IS_AUTHENTICATED_FULLY")]
//    public function upload(Request $request, FileUploader $fileUploader): Response
//    {
//        foreach ($request->files as $file) {
//            $newPic = new Picture();
//            $newPic->setFile($file);
//            $fileName = $fileUploader->upload($newPic->getFile(), FileUploader::TRICK_PICTURE_DIRECTORY);
//            $newPic->setPath($fileName);
//            $this->getDoctrine()->getManager()->persist($newPic);
//        }
//        $this->getDoctrine()->getManager()->flush();
//
//        return $this->redirectToRoute();
//    }
}
