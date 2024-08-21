<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/customers', name: 'get_customers', methods: ['GET'])]
    public function getCustomers(): JsonResponse
    {
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'fullName' => $customer->getFullName(),
                'email' => $customer->getEmail(),
                'country' => $customer->getCountry(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/customers/{customerId}', name: 'get_customer', methods: ['GET'])]
    public function getCustomer(int $customerId): JsonResponse
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($customerId);

        if (!$customer) {
            return new JsonResponse(['message' => 'Customer not found'], 404);
        }

        $data = [
            'fullName' => $customer->getFullName(),
            'email' => $customer->getEmail(),
            'username' => $customer->getUsername(),
            'gender' => $customer->getGender(),
            'country' => $customer->getCountry(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone(),
        ];

        return new JsonResponse($data);
    }
}
