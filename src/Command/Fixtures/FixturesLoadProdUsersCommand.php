<?php

namespace App\Command\Fixtures;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:fixtures:load_prod:users',
    description: 'Use this command to fill the db with production ready admin users',
)]
class FixturesLoadProdUsersCommand extends AbstractFixtureCommand
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($entityManager);
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Start loading admin Users into database');

        $users = $this->createAdminUsers();
        $manager = $this->getEntityManager();
        $advance = \count($users);

        $io->progressStart($advance);

        foreach ($users as $user) {
            $manager->persist($user);
            $io->progressAdvance();
        }
        $io->progressFinish();
        $manager->flush();

        $io->success('Successfully loaded admin users');

        return Command::SUCCESS;
    }

    /**
     * @return User[]
     */
    private function createAdminUsers(): array
    {
        $users = [];

        $usersCredentials = [
          'Jimmy Sweat' => [
              'admin@snowtricks.com' => 'Azerty123*',
          ],
        ];

        foreach ($usersCredentials as $username => $credentials) {
            foreach ($credentials as $email => $plainPassword) {
                $users[] = $this->createAdminUser($email, $username, $plainPassword);
            }
        }
        return $users;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $plainPassword
     * @return User
     */
    private function createAdminUser(string $email, string $username, string $plainPassword): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $user->setProfilePicturePath('uploads/user_pictures/adminPP.jpg');
        $user->setIsVerified(true);

        return $user;
    }
}
