<?php

namespace App\Command;

use App\DataFixtures\UserFixtureLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->setName('app:create:user')
            ->setDescription('Creates a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new Loader();
        $loader->addFixture(new UserFixtureLoader());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->manager, $purger);
        $executor->execute($loader->getFixtures());
    }

}