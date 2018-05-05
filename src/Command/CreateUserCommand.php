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
    private $loader;
    private $executor;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->setName('app:fill:database')
            ->setDescription('Creates a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init();
        $this->executor->execute($this->loader->getFixtures());
    }

    private function init()
    {
        $this->loader = new Loader();
        $this->loader->addFixture(new UserFixtureLoader());

        $purger = new ORMPurger();
        $this->executor = new ORMExecutor($this->manager, $purger);
    }
}