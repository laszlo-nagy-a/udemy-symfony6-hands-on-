<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        #hashelő interface
        private UserPasswordHasherInterface $userPasswordHasherInterface
    ){}

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user1,
                '123456789'
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('john@test.com');
        $user2->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user2,
                '123456789'
            )
        );
        $manager->persist($user2);

        //készísünk új példányt a classból
        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welcome to Poland!');
        $microPost1->setText('Welcome to Poland!');
        $microPost1->setAuthor($user1);
        $microPost1->setCreated(new DateTime());        
        // sql query-t készít elő
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welcome to US!');
        $microPost2->setText('Welcome to US!');
        $microPost2->setAuthor($user2);
        $microPost2->setCreated(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welcome to Germany!');
        $microPost3->setText('Welcome to Germany!');
        $microPost3->setAuthor($user1);
        $microPost3->setCreated(new DateTime());
        $manager->persist($microPost3);
        
        // sql query-t végrehajta
        $manager->flush();
    }
}
