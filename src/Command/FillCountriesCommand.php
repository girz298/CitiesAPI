<?php

namespace App\Command;

use App\Entity\Api\City;
use App\Entity\Api\Continent;
use App\Entity\Api\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillCountriesCommand extends Command
{
    private $countriesFilePath;
    private $citiesFilePath;

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
        $file = new \SplFileObject($this->getCountriesFilePath());

        $file->fgetcsv();
        while ($line = $file->fgetcsv()) {
            $continentCode = $line[2] ?? null;
            $continentName = $line[3] ?? null;

            $countryCode = $line[4] ?? null;
            $countryName = $line[5] ?? null;

            if ($continentCode && $continentName) {
                $continent = $this->createOrGetContinent($continentCode, $continentName, $output);

                if ($countryCode && $countryName) {
                    $this->createOrGetCountry($continent, $countryCode, $countryName, $output);
                }
            }
        }

        $citiesFile = new \SplFileObject($this->citiesFilePath);

        $citiesFile->fgetcsv();

        while ($line = $citiesFile->fgetcsv()) {
            $cityName = $line[10] ?? null;
            $countryName = $line[5] ?? null;

            if ($countryName && $cityName) {
                $this->createCity($countryName, $cityName, $output);
            }
        }
    }

    public function setContinentsFilePath(string $filePath)
    {
        $this->countriesFilePath = $filePath;
    }

    public function setCitiesFilePath(string $filePath)
    {
        $this->citiesFilePath = $filePath;
    }

    public function setManager(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getCountriesFilePath(): string
    {
        return $this->countriesFilePath;
    }

    private function createOrGetContinent(string $isoCode, string $name, OutputInterface $output): Continent
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

        $output->writeln(
            sprintf(
                'Created continent %s', $newContinent->getName())
        );

        return $newContinent;
    }

    public function createOrGetCountry(Continent $continent, string $code, string $name, OutputInterface $output): Country
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

        $output->writeln(
            sprintf(
                'Created country %s', $newCountry->getName())
        );

        return $newCountry;
    }

    public function createCity(string $countryName, string $cityName, OutputInterface $output)
    {
        $countryRepo = $this->manager->getRepository(Country::class);

        $country = $countryRepo->findOneByName($countryName);

        if ($country) {
            $city = new City();

            $city->setCountry($country);
            $city->setName($cityName);

            $this->manager->persist($city);
            $this->manager->flush();


            $output->writeln(
                sprintf(
                    'Created city %s', $city->getName())
            );
        }
    }
}