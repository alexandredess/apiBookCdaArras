<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $livre = new Book;
            $livre->setTitle(title: 'Livre ' . $i);
            $livre->setCoverText(coverText: 'Quatrième de couverture numéro :' . $i);
            $manager->persist(object: $livre);
    }
    

        $manager->flush();
    }
}
