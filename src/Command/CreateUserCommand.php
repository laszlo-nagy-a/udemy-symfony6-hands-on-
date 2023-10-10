<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user account.',
)]
// console command hívására jó -> symfony console app:create-user arg1 arg2
class CreateUserCommand extends Command
{
    public function __construct(
        #hashelő interface
        private UserPasswordHasherInterface $userPasswordHasherInterface,
        private UserRepository $userRepository
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User e-mail')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user,
                $password
            )
        );
        $this->userRepository->add($user, true);
        
        if ($email || $password) {
            $io->note(sprintf('You passed an argument: email -> %s | password -> %s', $email, $password));
        }

        $io->success(sprintf('User with %s email account was created!', $email));

        return Command::SUCCESS;
    }
}
