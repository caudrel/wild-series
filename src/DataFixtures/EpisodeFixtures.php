<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 11; $i++) {
            $episode = new Episode();
            $episode
                ->setTitle($faker->title())
                ->setNumber($faker->unique()->numberBetween(1, 10))
                ->setSynopsis($faker->paragraph(5))
                ->setSeason($this->getReference('season_'. $faker->numberBetween(1, 5)));

            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}