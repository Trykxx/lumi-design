<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('front/home', name: 'front_home_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductRepository $repository, Request $request): Response
    {
        $pagination = $repository->paginateProductOrderByUpdatedAt($request->query->getInt('page', 1));

        return $this->render('front/home/index.html.twig', [
            'products' => $pagination,
        ]);
    }

    #[Route('/detail/{slug}', name: 'show', methods:['GET'])]
    public function show(ProductRepository $repository, string $slug): Response
    {
        $product = $repository->findWithCategory($slug);

        return $this->render('front/home/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/conditions', name: 'conditions')]
    public function conditions(){

        return $this->render('front/registration/conditions.html.twig');
    }

}
