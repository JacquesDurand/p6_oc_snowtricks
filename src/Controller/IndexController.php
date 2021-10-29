<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/{page<\d+>}', name: 'index')]
    public function index(TrickRepository $trickRepository, int $page = 1): Response
    {
        $qb = $trickRepository->createFindAllOrderedByCreatedDateQueryBuilder();

        $pager = new Pagerfanta(new QueryAdapter($qb));
        $pager->setMaxPerPage(4);
        $pager->setCurrentPage($page);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'pager' => $pager
        ]);
    }
}
