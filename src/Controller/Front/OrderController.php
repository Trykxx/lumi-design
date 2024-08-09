<?php

namespace App\Controller\Front;

use App\Entity\OrderItem;
use App\Entity\Orders;
use App\Repository\OrdersRepository;
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
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em,CartService $cartService, OrderService $orderService)
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        $cart = $cartService->getCart();

        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('front_cart_index');
        }

        $order = $orderService->createOrder($user, $cart);

        $em->persist($order);
        $em->flush();

        $cartService->clearCart();

        $this->addFlash('success', 'Commande effectué avec succès');
        return $this->redirectToRoute('front_order_confirmation', ['id' => $order->getId()]);
    }

    #[Route('/confirmation/{id}', name: 'confirmation', methods: ['GET'])]
    public function confirmation(Orders $order, OrdersRepository $repository, int $id)
    {
        $order = $repository->findOrderWithRelations($id);

        return $this->render('front/order/confirmation.html.twig', [
            'order' => $order,
        ]);
    }
}
