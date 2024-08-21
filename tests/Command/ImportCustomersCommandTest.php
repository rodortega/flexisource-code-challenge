<?php

namespace App\Tests\Command;

use App\Command\ImportCustomersCommand;
use App\Service\Customer\CustomerImporter;
use App\Service\DataProvider\DataProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCustomersCommandTest extends TestCase
{
    private $dataProvider;
    private $entityManager;
    private $customerImporter; // Changed to mock
    private ImportCustomersCommand $command;

    protected function setUp(): void
    {
        $this->dataProvider = $this->createMock(DataProviderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->customerImporter = $this->createMock(CustomerImporter::class);

        $this->command = new ImportCustomersCommand($this->customerImporter);
    }

    public function testExecuteSuccess(): void
    {
        $this->customerImporter->expects($this->once())
            ->method('importCustomers')
            ->with($this->equalTo(100));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertStringContainsString('Customers imported successfully!', $commandTester->getDisplay());
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }
}
