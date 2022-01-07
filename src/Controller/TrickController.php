<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickCommentType;
use App\Form\TrickType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PictureRepository;
use App\Repository\TrickRepository;
use App\Service\File\FileUploader;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/new', name: 'trick_new', methods: ['GET','POST'])]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function new(Request $request, Security $security, FileUploader $fileUploader): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('pictures')->getData();

            /** @var Picture $picture */
            foreach ($pictures as $picture) {
                $fileName = $fileUploader->upload($picture->getFile(), FileUploader::TRICK_PICTURE_DIRECTORY);
                $picture->setPath($fileName);
                $trick->addPicture($picture);
            }

            $user = $security->getUser();
            $trick->setAuthor($user);

            $trick->setState(Trick::TRICK_STATE_AVAILABLE);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'trickForm' => $form,
        ]);
    }

    #[Route('/{slug}/{page<\d+>}', name: 'trick_show', methods: ['GET', 'POST'])]
    public function show(Trick $trick, Security $security, Request $request, CommentRepository $commentRepository, CategoryRepository $categoryRepository, int $page = 1): Response
    {
        $qb = $commentRepository->createFindAllForTrickOrderedByCreatedAtQueryBuilder($trick);

        $pager = new Pagerfanta(new QueryAdapter($qb));
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($page);

        $form = $this->createForm(TrickCommentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Comment $comment */
            foreach ($form->get('comments')->getData() as $comment) {
                $comment->setAuthor($security->getUser());
                $trick->addComment($comment);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }

        $categories = $categoryRepository->findAll();

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $form->createView(),
            'allCategories' => $categories,
            'pager' => $pager
        ]);
    }

    #[Route('/{slug}/edit', name: 'trick_edit', methods: ['GET','POST'])]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function edit(Request $request, Trick $trick, Security $security, FileUploader $fileUploader): Response
    {
        $pictures = $trick->getPictures();
        $clones = [];
        foreach ($pictures as $picture) {
            $clones[] = clone $picture;
        }

        $csrfToken = $request->get('token');
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isCsrfTokenValid('trick_edit', $csrfToken)) {
            $uploadedPictures = $form->get('pictures')->getData();

            /** @var Picture $picture */
            foreach ($uploadedPictures as $uploadedPicture) {
                if ($uploadedPicture->getFile()) {
                    $fileName = $fileUploader->upload($uploadedPicture->getFile(), FileUploader::TRICK_PICTURE_DIRECTORY);
                    $uploadedPicture->setPath($fileName);
                    $trick->addPicture($uploadedPicture);
                }
            }

            foreach ($clones as $picture) {
                $trick->addPicture($picture);
            }

            $user = $security->getUser();
            $trick->setAuthor($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}/pictures/{id}/delete', name: 'trick_delete_picture', methods: ['DELETE'])]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function deletePicture(Request $request, TrickRepository $repository, PictureRepository $pictureRepository): Response
    {
        $trick = $repository->findOneBy(['slug' => $request->get('slug')]);
        $picture = $pictureRepository->find($request->get('id'));
        if ($this->isCsrfTokenValid('delete'.$trick->getId().'picture'.$picture->getId(), $request->request->get('_token'))) {
            $trick->removePicture($picture);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}/pictures/{id}/update', name: 'trick_update_picture', methods: ['POST'])]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function updatePicture(Request $request, TrickRepository $repository, PictureRepository $pictureRepository, FileUploader $fileUploader): Response
    {
        $trick = $repository->findOneBy(['slug' => $request->get('slug')]);
        $picture = $pictureRepository->find($request->get('id'));
        if ($this->isCsrfTokenValid('update'.$trick->getId().'picture'.$picture->getId(), $request->request->get('_token'))) {
            foreach ($request->files as $file) {
                if (null !== $file) {
                    $newPic = new Picture();
                    $newPic->setFile($file);
                    $fileName = $fileUploader->upload($newPic->getFile(), FileUploader::TRICK_PICTURE_DIRECTORY);
                    $newPic->setPath($fileName);
                    $trick->addPicture($newPic);
                }
            }
            $trick->removePicture($picture);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();
        }
        return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
