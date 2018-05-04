<?php

namespace App\DataFixtures;

use App\Entity\Security\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtureLoader implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('db_user');
        $user->setPassword('db_password');
        $user->setRoles(['ROLE_API_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}