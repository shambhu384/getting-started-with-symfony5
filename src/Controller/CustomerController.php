<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Message\CreateCustomer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Cache\CacheInterface;
use App\Repository\CustomerRepository;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/customer", name="customer_")
 */
class CustomerController
{
    /**
     * @Route("/", name="add")
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, MessageBusInterface $bus)
    {
        $uuid =  uuid_create(UUID_TYPE_RANDOM);
        $customer = new Customer($uuid);

        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $customer]);
        $errors = $validator->validate($customer);

        if (count($errors) > 0) {
            $errors = $serializer->serialize($errors, 'json', ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]);
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }

        $message = new CreateCustomer($customer);
        $bus->dispatch($message);

        return new Response(
            $serializer->serialize($customer, 'json', ['groups' => ['private']]),
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/{id}", name="get")
     */
    public function get($id, CacheInterface $cache, SerializerInterface $serializer, CustomerRepository $customerRepository)
    {

        if(!$customer = $cache->get($id, function (ItemInterface $item) use ($id, $customerRepository) {
            $item->expiresAfter(3600);
            $customer = $customerRepository->find($id);
            return $customer;
        })) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $serializer->serialize($customer, 'json', ['groups' => ['private']]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
