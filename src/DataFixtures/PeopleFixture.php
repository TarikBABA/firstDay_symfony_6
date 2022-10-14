<?php

namespace App\DataFixtures;


use App\Entity\People;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PeopleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {

            $person = new People();
            $person->setFirstname($faker->firstName);
            $person->setName($faker->name);
            $person->setAge($faker->numberBetween(18, 65));

            $manager->persist($person);
        }
        $manager->flush();
    }
}