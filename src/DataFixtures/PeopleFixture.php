<?php

namespace App\DataFixtures;

require_once 'vendor/autoload.php';

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PeopleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = factory
        // for ($i = 0; $i < 50; $i++) {

        //     $person = new People();
        //     $person->setFirstname($faker->firstName);
        //     $person->setName($faker->name);
        //     $person->setAge($faker->age);
        // }
        $manager->flush();
    }
}