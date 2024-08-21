<?php

// src/Command/ImportCustomersCommand.php

namespace App\Command;

use App\Service\Customer\CustomerImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-customers',
    description: 'Imports customers from the third-party API.',
    hidden: false,
    aliases: ['app:fetch-customers']
)]
class ImportCustomersCommand extends Command
{
    private $customerImporter;

    public function __construct(CustomerImporter $customerImporter)
    {
        parent::__construct();
        $this->customerImporter = $customerImporter;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->customerImporter->importCustomers();
        $output->writeln('Customers imported successfully!');

        return Command::SUCCESS;
    }
}
