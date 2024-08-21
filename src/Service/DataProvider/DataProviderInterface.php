<?php

namespace App\Service\DataProvider;

interface DataProviderInterface
{
    public function fetchCustomers(int $count): array;
}
