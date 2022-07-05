<?php

namespace App\DataFixtures;

use App\Entity\Baker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Cake;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CakeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i < 317; $i++) {
            $cake = new Cake();
            $cake->setCreated($faker->dateTimeInInterval('+1 months', '+1 months'));
            $name = $faker->randomElement([
                'Gâteau d\'anniversaire',
                'Forêt noire',
                'Pièce montée au caramel et fruits rouges',
                'Gâteau licorne',
                'Gâteau d\'anniversaire Peppa Pig',
                'Fraisier',
                'Gâteau à étages, crème au beurre',
                'Baba au rhum',
                'Gâteau au twix, mars et coulis de kinder surprise',
            ]);
            if (is_string($name)) {
                $cake->setName($name);
            }
            $cake->setCategory($faker->randomElement([
                "Pièce montée",
                "Cupcake(s)",
                "Spécialité(s) étrangère(s)",
                "Mini gâteau",
                "Patisserie(s)",
                "Gâteau junior",
                "Gâteau sculpté",
                "Magnum cake(s)",
                "Pop cake(s)",
            ]));
            $cake->setAllergens("Gâteau diabétique");
            $cake->setPicture1($faker->imageUrl(640, 640, 'photo d\'un gâteau'));
            $cake->setDescription($faker->text(250));
            $cake->setPrice($faker->randomFloat(2, 12, 50));
            $size = $faker->randomElement(([
                '10/12 parts',
                '14/16 parts',
                '18/20 parts',
            ]));
            if (is_string($size)) {
                $cake->setSize($size);
            }
            $reference = $faker->numberBetween(0, 49);
            $baker = $this->getReference('user_' . $reference . '_baker_' . $reference);
            if ($baker instanceof Baker) {
                $cake->setBaker($baker);
            }
            $manager->persist($cake);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return
            [
                BakerFixtures::class,
            ];
    }
}
