<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;
    public function __construct(private RequestStack $requestStack, private ProductRepository $repository)
    {
        $this->session = $this->requestStack->getSession();
    }

    public function getCart()
    {
        return $this->session->get('cart', []);
    }

    public function getCartContent()
    {
        $cart = $this->session->get('cart', []);
        $dataCart = [];
        $totalCart = 0;

        foreach ($cart as $id => $quantity) {
            $product = $this->repository->find($id);
            if (!$product) {
                continue;
            }

            $total = $product->getPrice() * $quantity;

            $dataCart[] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $total
            ];

            $totalCart = $totalCart + $total;
        }
        return ['dataCart' => $dataCart,'total' => $totalCart];
    }

    public function incrementProductQuantity(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $this->session->set('cart', $cart);
    }

    public function decreaseProductQuantity(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $this->session->set('cart', $cart);
    }

    public function removeProduct(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function clearCart()
    {
        $this->session->remove('cart');
    }
}
