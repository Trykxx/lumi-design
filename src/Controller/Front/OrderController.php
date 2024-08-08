<?php

namespace App\Controller\Front;

use App\Entity\OrderItem;
use App\Entity\Orders;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/commande', name: 'front_order_')]
class OrderController extends AbstractController
{

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/', name: 'index')]
    public function index(EntityManagerInterface $em,CartService $cartService, OrderService $orderService)
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        $cart = $cartService->getCart();

        $order = $orderService->createOrder($user, $cart);

        $em->persist($order);
        $em->flush();

        $cartService->clearCart();

        $this->addFlash('success', 'Commande effectué avec succès');
        return $this->redirectToRoute('front_home_index');
    }
}
