<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(private RequestStack $requestStack, private ProductRepository $repository)
    {
    }

    public function getSession()
    {
        return $this->requestStack->getSession();
    }
    public function getCart()
    {
        return $this->getSession()->get('cart', []);
    }

    public function getCartContent()
    {
        $cart = $this->getSession()->get('cart', []);
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
        return ['dataCart' => $dataCart,'totalCart' => $totalCart];
    }

    public function incrementProductQuantity(int $id)
    {
        $cart = $this->getSession()->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $this->getSession()->set('cart', $cart);
    }

    public function decreaseProductQuantity(int $id)
    {
        $cart = $this->getSession()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $this->getSession()->set('cart', $cart);
    }

    public function removeProduct(int $id)
    {
        $cart = $this->getSession()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->getSession()->set('cart', $cart);
    }

    public function clearSession()
    {
        $this->getSession()->remove('cart');
    }
}
