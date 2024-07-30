<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/produit', name: 'adminproduit')]
class ProductController extends AbstractController
{
    #[Route('/',name:'index')]
    public function index(ProductRepository $repository,Request $request){
        $produits=$repository->paginateProduct($request->query->getInt('page',1));
        return $this->render('admin/index.html.twig',[
            'produits'=>$produits
        ]);
    }
}
