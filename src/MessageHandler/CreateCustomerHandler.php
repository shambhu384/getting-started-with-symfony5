<?php

namespace App\MessageHandler;

use App\Message\CreateCustomer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CreateCustomerHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $cache;

    public function __construct(EntityManagerInterface $entityManager, CacheInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    public function __invoke(CreateCustomer $createCustomer)
    {
        $customer = $createCustomer->getCustomer();
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $this->cache->get($customer->getId(), function (ItemInterface $item) use ($customer) {
            $item->expiresAfter(3600);
            return $customer;
        });
    }
}
