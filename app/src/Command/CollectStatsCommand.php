<?php

namespace App\Command;

use App\Document\SiteStat;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Repository\CreditTransactionRepository;
use App\Service\StatCollectorService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:collect-stats',
    description: 'Collecte les statistiques quotidiennes de la plateforme.',
)]
class CollectStatsCommand extends Command
{
    public function __construct(
        private TripRepository $tripRepository,
        private CreditTransactionRepository $transactionRepository,
        private UserRepository $userRepository,
        private DocumentManager $documentManager,
        private StatCollectorService $statCollector
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    $this->statCollector->collect();
    $output->writeln('<info>Statistiques enregistrées avec succès.</info>');
    return Command::SUCCESS;
    }

}

