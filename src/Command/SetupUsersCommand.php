<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

#[AsCommand(
    name: 'app:setup-users',
    description: 'Add a short description for your command',
)]
class SetupUsersCommand extends Command
{
  
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->em;

        $email = 'demo@localhost.loc';
        $repository = $em->getRepository(User::class);
        $account = $repository->findOneByEmail($email);
        
        if (!$account) {
          $account = new User();
          //$account->setUsername('system');
          $account->setPassword('1234');
          $account->setEmail($email);
          $io->success('Create ' . $email);
        }


        $em->persist($account);
        $em->flush();

        return Command::SUCCESS;
    }
}
