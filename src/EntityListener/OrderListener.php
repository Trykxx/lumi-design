<?php

namespace App\EventListener;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs as EventLifecycleEventArgs;

#[AsEntityListener(event: 'prePersist', entity: Orders::class)]
class OrderListener
{
    public function prePersist(Orders $order, EventLifecycleEventArgs $args)
    {
        var_dump('OrderListener prePersist called');

        if (null === $order->getOrderNumber()) {
            $orderNumber = $this->generateOrderNumber();
            $order->setOrderNumber($orderNumber);
        }
    }

    private function generateOrderNumber(): string
    {
        // Obtenir la date actuelle
        $date = new \DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        // Générer une suite de chiffres aléatoires
        $randomDigits = mt_rand(100000, 999999); // 6 chiffres aléatoires

        // Construire le numéro de commande
        // return sprintf('LD%s%s%s%s', $year, $month, $day, $randomDigits);
        return 'TEST';
    }
}
