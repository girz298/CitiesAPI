<?php

namespace App\DataFixtures;

use App\Entity\Security\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtureLoader implements FixtureInterface
{

    private $userFields = [
        [
            'username' => 'db_user',
            'password' => 'db_password',
            'roles' => ['ROLE_API_USER'],
        ],
        [
            'username' => 'michael',
            'password' => 'michael',
            'roles' => ['ROLE_API_USER'],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->userFields as $fields) {
            $user = new User();
            $user->setUsername($fields['username']);
            $user->setPassword($fields['password']);
            $user->setRoles($fields['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}