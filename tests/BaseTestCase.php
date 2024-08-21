<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

abstract class BaseTestCase extends WebTestCase
{
    protected static ?\Symfony\Bundle\FrameworkBundle\KernelBrowser $client = null;
    protected static ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        parent::setUp();

        self::$client = static::createClient();

        $container = self::$client->getContainer();

        self::$entityManager = $container->get('doctrine')->getManager();

        $this->createSchema();
    }

    protected function tearDown(): void
    {
        $this->dropSchema();

        parent::tearDown();
    }

    private function createSchema(): void
    {
        $schemaTool = new SchemaTool(self::$entityManager);
        $metadata = self::$entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);
    }

    private function dropSchema(): void
    {
        $schemaTool = new SchemaTool(self::$entityManager);
        $metadata = self::$entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
    }
}
