<?php

namespace App\Tests\Factory;

use App\Entity\Customer;
use Faker\Factory as FakerFactory;

class CustomerFactory
{
    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function createCustomer(): Customer
    {
        $customer = new Customer();
        $customer->setEmail($this->faker->email());
        $customer->setFirstName($this->faker->firstName());
        $customer->setLastName($this->faker->lastName());
        $customer->setUsername($this->faker->userName());
        $customer->setGender($this->faker->randomElement(['male', 'female']));
        $customer->setCountry($this->faker->country());
        $customer->setCity($this->faker->city());
        $customer->setPhone($this->faker->phoneNumber());
        $customer->setPassword(md5($this->faker->password()));

        return $customer;
    }

    public function toArray(Customer $customer): array
    {
        return [
            'email' => $customer->getEmail(),
            'name' => [
                'first' => $customer->getFirstName(),
                'last' => $customer->getLastName(),
            ],
            'login' => [
                'username' => $customer->getUsername(),
                'password' => $customer->getPassword(),
            ],
            'gender' => $customer->getGender(),
            'location' => [
                'country' => $customer->getCountry(),
                'city' => $customer->getCity(),
            ],
            'phone' => $customer->getPhone(),
        ];
    }
}

