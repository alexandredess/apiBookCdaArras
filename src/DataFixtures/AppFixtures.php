<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Création des auteurs
        $listAuthor=[];
        for ($i =0; $i<10; $i++){
            $author = new Author;
            $author->setFirstName(firstName: "Prenom ".$i);
            $author->setLastName(lastName: "Nom ".$i);
            $manager->persist(object: $author);
            // On stocke les auteurs dans un tableau pour les réutiliser plus tard
            $listAuthor[]=$author;
        }

        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $livre = new Book;
            $livre->setTitle(title: 'Livre ' . $i);
            $livre->setCoverText(coverText: 'Quatrième de couverture numéro :' . $i);

            // On associe un auteur au livre
            $livre->setAuthor($listAuthor[array_rand($listAuthor)]);
            $manager->persist(object: $livre);
    }
    

        $manager->flush();
    }
}
