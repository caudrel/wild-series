<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
    public function __construct(SluggerInterface $sluggerInterface) {
        $this->slugger = $sluggerInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 11; $i++) {
            $episode = new Episode();
            $episode
                ->setTitle($faker->sentence(4))
                ->setNumber($faker->unique()->numberBetween(1, 10))
                ->setSynopsis($faker->paragraph(5))
                ->setSeason($this->getReference('season_'. $faker->numberBetween(1, 5)))
                ->setDuration($faker->numberBetween(10, 130));
            $slug = $this->slugger->slug($episode->getTitle());
            $episode->setSlug($slug);

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