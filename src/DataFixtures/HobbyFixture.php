<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Lire",
            "Écrire",
            "Jouer",
            "Le Yoga",
            "Courir",
            "Le cyclisme",
            "Apprendre une langue",
            "Cuisiner",
            "Devenez un contributeur sur Wikipédia",
            "Passionnez-vous pour les documentaires",
            "Lancez un club de cuisine",
            "jardinage",
            "Faites des puzzles",
            "Pratiquer la méditation"
        ];
        for ($i = 0; $i < count($data); $i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);
        }

        $manager->flush();
    }
}