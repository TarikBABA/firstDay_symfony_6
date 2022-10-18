<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profil1 = new Profile();
        $profil1->setRs('LocalHost');
        $profil1->setUrl('http://localhost:8080');

        $profil2 = new Profile();
        $profil2->setRs('Git');
        $profil2->setUrl('https://github.com/TarikBABA');

        $profil3 = new Profile();
        $profil3->setRs('WebSite');
        $profil3->setUrl('https://babakhouya-tarik.netlify.app');

        $manager->persist($profil1);
        $manager->persist($profil2);
        $manager->persist($profil3);

        $manager->flush();
    }
}