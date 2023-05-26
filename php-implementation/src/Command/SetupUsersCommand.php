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
use App\Entity\Group;

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

        $role1 = $em->getRepository(Group::class)->findOneByName('ROLE_USER');
        if (!$role1) {
          $role1 = new Group();
          $role1->setName('ROLE_USER');
          $em->persist($role1);
          $io->success('Create ' . $role1);
        }
        
        $role2 = $em->getRepository(Group::class)->findOneByName('ROLE_EDITOR');
        if (!$role2) {
          $role2 = new Group();
          $role2->setName('ROLE_EDITOR');
          $em->persist($role2);
          $io->success('Create ' . $role2);
        }
        
        $role3 = $em->getRepository(Group::class)->findOneByName('ROLE_ADMIN');
        if (!$role3) {
          $role3 = new Group();
          $role3->setName('ROLE_ADMIN');
          $em->persist($role3);
          $io->success('Create ' . $role3);
        }
        
        $role4 = $em->getRepository(Group::class)->findOneByName('ROLE_DEV');
        if (!$role4) {
          $role4 = new Group();
          $role4->setName('ROLE_DEV');
          $em->persist($role4);
          $io->success('Create ' . $role4);
        }

        $account = $repository->findOneByEmail($email);
        if (!$account) {
          $account = new User();
          //$account->setUsername('system');
          $account->setPassword('1234');
          $account->setEmail($email);
          $io->success('Create ' . $email);
        }
        
        $account->addGroup($role1);
        $em->persist($account);

        $em->flush();

        return Command::SUCCESS;
    }
}
