<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    CONST PROGRAMS = [
        ['name' => 'The Walking Dead',
            'synopsis' => 'Des zombies envahissent la terre.',
            'category'=> 'Horreur',
            ],
        ['name' => 'La Reine Charlotte',
            'synopsis' => "Promise au Roi d'Angleterre contre son gré, Charlotte arrive à Londres et découvre que la famille royale n'est pas ce qu'elle imaginait.",
            'category'=> 'Drame',
            ],
        ['name' => 'The Mandalorian',
            'synopsis' => "Le Mandalorien se situe après la chute de l'Empire et avant l'émergence du Premier Ordre.",
            'category'=> 'Fantastique',
            ],
        ['name' => 'Firefly Lane',
            'synopsis' => "Sur trente ans, les hauts et les bas de Kate et Tully qui, depuis l'adolescence, sont meilleures amies et se soutiennent dans les bons comme les mauvais moments.",
            'category'=> 'Drame',
            ],
        ['name' => 'The Boys',
            'synopsis' => "Le monde est rempli de super-héros qui sont gérés par la société Vought International. Elle s’occupe de leur promotion et leur commercialisation. Ils ne sont pas tous héroïques et parfaits.",
            'category'=> 'Fantastique',
            ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $sousProgram) {
            $program = new Program();
            $program->setName($sousProgram ['name']);
            $program->setSynopsis($sousProgram ['synopsis']);
            $program->setCategory($this->getReference('category_' . $sousProgram['category']));
            $manager->persist($program);
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
