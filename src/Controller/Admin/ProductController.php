<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/product', name: 'admin_product_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductRepository $repository, Request $request)
    {
        $pagination = $repository->paginateProduct($request->query->getInt('page', 1));

        return $this->render('admin/product/index.html.twig', [
            'products' => $pagination
        ]);
    }

    #[Route('/detail/{slug}', name: 'show', methods:['GET'])]
    public function show(ProductRepository $repository, string $slug): Response
    {
        $product = $repository->findWithCategory($slug);

        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/create', name: 'create', methods:['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //valid declenche la verification des erreurs
            // $file = $form->get('thumbnailFile')->getData();

            

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/create.html.twig', [
            'productForm' => $form,
        ]);
    }
}
