<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickCommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\File\FileUploader;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'trick_new', methods: ['GET','POST'])]
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

            //TODO obv
            $trick->setState('toto');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/{page<\d+>}', name: 'trick_show', methods: ['GET', 'POST'])]
    public function show(Trick $trick, Security $security, Request $request, CommentRepository $commentRepository, int $page = 1): Response
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

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $form->createView(),
            'pager' => $pager
        ]);
    }

    #[Route('/{slug}/edit', name: 'trick_edit', methods: ['GET','POST'])]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
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
}
