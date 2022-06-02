<?php

namespace App\DataFixtures;

use App\Entity\Interfaces\IRoles;
use App\Entity\Poste;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture implements IRoles
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $availableServices = ['direction', 'comptabilité', 'IT'];
        $availablePosts = ['President', 'DG', 'CTO', 'DAF', 'Devops', 'Dev', 'Comptabilité'];

        for ($i = 0; $i < count($availableServices); $i++) {
            $service = new Service();
            $service->setNom($availableServices[$i]);
            $service->setDescription($faker->name);

            $manager->persist($service);
        }
        $manager->flush();


        for ($i = 0; $i < count($availablePosts); $i++) {
            $post = new Poste();
            $post->setNom($availablePosts[$i]);
            $post->setDescription($faker->name);

            $manager->persist($post);
        }
        $manager->flush();


        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setDateInscription($faker->dateTime);
            $user->setPassword($this->userPasswordHasher->hashPassword($user,"coucou"));
            $user->setRoles([self::ROLE_ADMIN]);
            $user->setEmail($faker->name);
            $user->setDateNaissance($faker->dateTime);
            $user->setDateEmploi($faker->dateTime);
            $user->setIdService(mt_rand(0, count($availableServices)));
            $user->setIdPoste(mt_rand(0, count($availablePosts)));

            $manager->persist($user);
        }
        $manager->flush();
    }
}
