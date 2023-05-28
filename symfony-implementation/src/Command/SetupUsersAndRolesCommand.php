<?php

namespace App\Command;

use App\DTO\RegisterUser;
use App\Entity\User;
use App\Entity\Group;
use App\Usecases\User\SetupAdminUsecase;
use App\Usecases\Group\SetupRolesUsecase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[AsCommand(
    name: 'app:setup-users',
    description: 'Add a short description for your command',
)]
class SetupUsersAndRolesCommand extends Command
{
  
    public function __construct(
        private SetupRolesUsecase $setupRoles,      
        private SetupAdminUsecase $setupAdmin,      
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        /*
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
        */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->setupRoles->execute(null);
        $this->setupAdmin->execute(null);

        return Command::SUCCESS;
    }
}
