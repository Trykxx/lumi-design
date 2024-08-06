<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/front/cart', name: 'front_cart_')]
class CartController extends AbstractController
{
    #[Route('/panier', name: 'index')]
    public function index(CartService $cartService): Response
    {
        $dataCart = $cartService->getCartContent();

        return $this->render('front/cart/index.html.twig', [
            // 'dataCart' => $data[$dataCart],
            // 'total' => $data[$totalCart],
        ]);
    }

    #[Route('/ajouter/{id}', name: 'add')]
    public function add($id, CartService $cartService)
    {
        $cartService->incrementProductQuantity($id);

        return $this->redirectToRoute('front_cart_index');
    }

    #[Route('/diminuer/{id}', name: 'decrease')]
    public function decrease($id, CartService $cartService)
    {
        $cartService->decreaseProductQuantity($id);

        return $this->redirectToRoute('front_cart_index');
    }

    #[Route('/supprimer/{id}', name: 'remove')]
    public function remove($id, CartService $cartService)
    {
        $cartService->removeProduct($id);

        return $this->redirectToRoute('front_cart_index');
    }

    #[Route('/vider', name: 'clear')]
    public function clear(CartService $cartService)
    {
        $cartService->clearSession();

        return $this->redirectToRoute('front_cart_index');
    }
}
