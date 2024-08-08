<?php

namespace App\Service;

use App\Entity\OrderItem;
use App\Entity\Orders;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class OrderService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function createOrder(User $user, array $cart)
    {
        $order = new Orders();
        // $order->setOrderNumber('LD7845825');
        $order->setCustomer($user);

        foreach ($cart as $id => $value) {
            $product = $this->repository->find($id);
            $price = $product->getPrice();

            $orderItem = new OrderItem();
            $orderItem->setQuantity($value);
            $orderItem->setPrice($price);
            $orderItem->setProduct($product);
            $order->addOrderItem($orderItem);
            // $em->persist($orderItem);
        }

        return $order;
    }
}