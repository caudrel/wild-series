<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 6; $i++){
            $season = new Season();
            $season
                ->setNumber($i)
                ->setYear(rand(2000, 2023))
                ->setDescription('Description ' . $i)
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