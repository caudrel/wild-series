<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $programRepository = $manager->getRepository(Program::class);
        $programs = $programRepository->findAll();

        for ($i=0 ; $i<10 ; $i++ ) {
            $actor = new Actor();
            $actor->setName($faker->name());
            $actor->addProgram($programs[array_rand($programs)]);
            $actor->addProgram($programs[array_rand($programs)]);
            $actor->addProgram($programs[array_rand($programs)]);
            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
