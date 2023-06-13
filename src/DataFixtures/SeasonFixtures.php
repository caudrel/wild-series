<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 6; $i++){
            $season = new Season();
            $season
                ->setNumber($faker->unique()->numberBetween(1,5))
                ->setYear($faker->year(2023))
                ->setDescription($faker->paragraph(5))
                ->setProgram($this->getReference('program_'. ProgramFixtures::PROGRAMS[$i]['title']));

            $manager->persist($season);
            $this->addReference('season_' . $season->getNumber(), $season);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}