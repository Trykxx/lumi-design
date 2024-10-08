<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-super-admin',
    description: 'create a super Administrator',
    aliases:['a:c:a']
)]
class CreateSuperAdminCommand extends Command
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher, private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the super admin')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password of the super admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        if (!$email) {
            $email = $io->ask("Choose an email for your super admin (e.g <comment>john.doe@gmail.com</comment>)");
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $password = $io->ask("Choose a password for your super admin (e.g <comment>Password45</comment>)");
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('User Super Admin créé avec succès ! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
