<?php

namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use App\Tests\Factory\CustomerFactory;

class CustomerControllerTest extends BaseTestCase
{
    private $customerFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerFactory = new CustomerFactory();
    }

    public function testGetAllCustomers(): void
    {
        $customer = $this->customerFactory->createCustomer();

        $entityManager = self::$entityManager;
        $entityManager->persist($customer);
        $entityManager->flush();

        self::$client->request('GET', '/customers');

        $this->assertResponseIsSuccessful();
        $this->assertJson(self::$client->getResponse()->getContent());

        $responseData = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertNotEmpty($responseData);
        $this->assertContains($customer->getFullName(), array_column($responseData, 'fullName'));
        $this->assertContains($customer->getEmail(), array_column($responseData, 'email'));
        $this->assertContains($customer->getCountry(), array_column($responseData, 'country'));
    }

    public function testGetSingleCustomer(): void
    {
        $customer = $this->customerFactory->createCustomer();
        $entityManager = self::$entityManager;
        $entityManager->persist($customer);
        $entityManager->flush();

        self::$client->request('GET', '/customers/' . $customer->getId());

        $this->assertResponseIsSuccessful();
        $this->assertJson(self::$client->getResponse()->getContent());

        $responseData = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('email', $responseData);
        $this->assertArrayHasKey('fullName', $responseData);
        $this->assertArrayHasKey('username', $responseData);
        $this->assertArrayHasKey('gender', $responseData);
        $this->assertArrayHasKey('country', $responseData);
        $this->assertArrayHasKey('city', $responseData);
        $this->assertArrayHasKey('phone', $responseData);

        $this->assertEquals($customer->getEmail(), $responseData['email']);
        $this->assertEquals($customer->getFullName(), $responseData['fullName']);
        $this->assertEquals($customer->getUsername(), $responseData['username']);
    }

    public function testGetCustomerNotFound(): void
    {
        self::$client->request('GET', '/customers/99999');

        $responseData = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(404);
        $this->assertJson(self::$client->getResponse()->getContent());
        $this->assertArrayHasKey('message', $responseData);
    }
}
