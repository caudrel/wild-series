<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    CONST PROGRAMS = [
        [
            'title' => 'The Walking Dead',
            'synopsis' => 'Des zombies envahissent la terre.',
            'category'=> 'Horreur',
            'year' => 2021,
            'poster' => 'https://picsum.photos/',
            'country' => 'USA',
            ],
        [
            'title' => 'La Reine Charlotte',
            'synopsis' => "Promise au Roi d'Angleterre contre son gré, Charlotte arrive à Londres et découvre que la famille royale n'est pas ce qu'elle imaginait.",
            'category'=> 'Drame',
            'year' => 2021,
            'poster' => 'https://picsum.photos/',
            'country' => 'USA',
            ],
        [
            'title' => 'The Mandalorian',
            'synopsis' => "Le Mandalorien se situe après la chute de l'Empire et avant l'émergence du Premier Ordre.",
            'category'=> 'Fantastique',
            'year' => 2021,
            'poster' => 'https://picsum.photos/',
            'country' => 'USA',
            ],
        [
            'title' => 'Firefly Lane',
            'synopsis' => "Sur trente ans, les hauts et les bas de Kate et Tully qui, depuis l'adolescence, sont meilleures amies et se soutiennent dans les bons comme les mauvais moments.",
            'category'=> 'Drame',
            'year' => 2020,
            'poster' => 'https://picsum.photos/',
            'country' => 'USA',
            ],
        [
            'title' => 'The Boys',
            'synopsis' => "Le monde est rempli de super-héros qui sont gérés par la société Vought International. Elle s’occupe de leur promotion et leur commercialisation. Ils ne sont pas tous héroïques et parfaits.",
            'category'=> 'Fantastique',
            'year' => 2019,
            'poster' => 'https://picsum.photos/',
            'country' => 'USA',
            ],
        [
            'title' => 'Arcane',
            'synopsis' => "Championnes de leurs villes jumelles et rivales (la huppée Piltover et la sous-terraine Zaun), deux sœurs Vi et Powder se battent dans une guerre où font rage des technologies magiques et des perspectives diamétralement opposées.",
            'category' => 'Animation',
            'year' => 2021,
            'poster' => 'https://picsum.photos/',
            'country' => 'USA',
            ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $program) {
            $programForFixture = new Program();
            $programForFixture
                ->setTitle($program ['title'])
                ->setSynopsis($program ['synopsis'])
                ->setCountry($program ['country'])
                ->setYear($program ['year'])
                ->setPoster($program ['poster'])
                ->setCategory($this->getReference('category_' . $program['category']));

            $manager->persist($programForFixture);

            $this->addReference('program_'.$program['title'], $programForFixture);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
