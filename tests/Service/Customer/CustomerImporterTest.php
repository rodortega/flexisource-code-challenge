<?php

namespace App\Tests\Service\Customer;

use App\Entity\Customer;
use App\Tests\Factory\CustomerFactory;
use App\Service\Customer\CustomerImporter;
use App\Service\DataProvider\DataProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class CustomerImporterTest extends TestCase
{
    private $dataProvider;
    private $entityManager;
    private $customerRepository;
    private $customerImporter;
    private $customerFactory;

    protected function setUp(): void
    {
        $this->dataProvider = $this->createMock(DataProviderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->customerRepository = $this->createMock(EntityRepository::class);
        $this->customerFactory = new CustomerFactory();

        $this->entityManager->method('getRepository')
            ->willReturn($this->customerRepository);

        $this->customerImporter = new CustomerImporter(
            $this->dataProvider,
            $this->entityManager
        );
    }

    public function testImportCustomersSuccess(): void
    {
        $mockData = [
            [
                'email' => 'test@example.com',
                'name' => ['first' => 'John', 'last' => 'Doe'],
                'login' => ['username' => 'johndoe', 'password' => 'password'],
                'gender' => 'male',
                'location' => ['country' => 'Australia', 'city' => 'Sydney'],
                'phone' => '1234567890',
            ]
        ];

        $this->dataProvider->expects($this->once())
            ->method('fetchCustomers')
            ->with(50)
            ->willReturn($mockData);

        $this->customerRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'test@example.com'])
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Customer::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->customerImporter->importCustomers(50);
    }

    public function testImportCustomersUpdate(): void
    {
        $mockData = [
            [
                'email' => 'existing@example.com',
                'name' => ['first' => 'Jane', 'last' => 'Doe'],
                'login' => ['username' => 'janedoe', 'password' => 'password'],
                'gender' => 'female',
                'location' => ['country' => 'Australia', 'city' => 'Melbourne'],
                'phone' => '0987654321',
            ]
        ];

        $existingCustomer = new Customer();
        $this->customerRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'existing@example.com'])
            ->willReturn($existingCustomer);

        $this->dataProvider->expects($this->once())
            ->method('fetchCustomers')
            ->with(100)
            ->willReturn($mockData);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->customerImporter->importCustomers(100);
    }
}
