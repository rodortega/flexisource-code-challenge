<?php

namespace App\Service\Customer;

use App\Entity\Customer;
use App\Service\DataProvider\DataProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

class CustomerImporter
{
    private $dataProvider;
    private $entityManager;

    public function __construct(DataProviderInterface $dataProvider, EntityManagerInterface $entityManager)
    {
        $this->dataProvider = $dataProvider;
        $this->entityManager = $entityManager;
    }

    public function importCustomers(int $count = 100): void
    {
        $customersData = $this->dataProvider->fetchCustomers($count);

        foreach ($customersData as $userData) {
            $this->saveCustomer($userData);
        }
    }

    private function saveCustomer(array $userData): void
    {
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneBy(['email' => $userData['email']]);

        if (!$customer) {
            $customer = new Customer();
        }

        $customer->setFirstName($userData['name']['first']);
        $customer->setLastName($userData['name']['last']);
        $customer->setEmail($userData['email']);
        $customer->setUsername($userData['login']['username']);
        $customer->setGender($userData['gender']);
        $customer->setCountry($userData['location']['country']);
        $customer->setCity($userData['location']['city']);
        $customer->setPhone($userData['phone']);
        $customer->setPassword(md5($userData['login']['password']));

        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }
}
