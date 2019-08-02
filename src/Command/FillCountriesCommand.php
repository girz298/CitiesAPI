<?php

namespace App\Command;

use App\Entity\Api\Continent;
use App\Entity\Api\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillCountriesCommand extends Command
{
    private $filePath;
    /** @var EntityManagerInterface */
    private $manager;

    protected function configure()
    {
        $this
            ->setName('app:fill:countries')
            ->setDescription('Creates a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new \SplFileObject($this->getFilePath());

        $file->fgetcsv();
        while ($line = $file->fgetcsv()) {
            $continentCode = $line[2] ?? null;
            $continentName = $line[3] ?? null;

            $countryCode = $line[4] ?? null;
            $countryName = $line[5] ?? null;

            if ($continentCode && $continentName) {
                $continent = $this->createOrGetContinent($continentCode, $continentName);

                if ($countryCode && $countryName) {
                    $this->createOrGetCountry($continent, $countryCode, $countryName);
                }
            }
        }
    }

    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function setManager(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    private function createOrGetContinent(string $isoCode, string $name): Continent
    {
        $continentRepo = $this->manager->getRepository(Continent::class);

        $continent = $continentRepo->findOneByName($name);

        if ($continent) {
            return $continent;
        }

        $newContinent = new Continent();
        $newContinent->setName($name);
        $newContinent->setCode($isoCode);

        $this->manager->persist($newContinent);
        $this->manager->flush();

        return $newContinent;
    }

    public function createOrGetCountry(Continent $continent, string $code, string $name): Country
    {
        $countryRepo = $this->manager->getRepository(Country::class);

        $country = $countryRepo->findOneByName($name);

        if ($country) {
            return $country;
        }

        $newCountry = new Country();

        $newCountry->setContinent($continent);
        $newCountry->setName($name);
        $newCountry->setIsoCode($code);

        $this->manager->persist($newCountry);
        $this->manager->flush();

        return $newCountry;
    }
}