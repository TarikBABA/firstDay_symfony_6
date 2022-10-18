<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Web Developer",
            "Database Engineer",
            "Engineering Manager",
            "Frontend Engineer",
            "Full Stack Engineer",
            "Information Security Technical Compliance Analyst",
            "Microsoft Web Developer",
            "Quality Assurance Analyst",
            "Quality Assurance Engineer",
            "Site Reliability Engineer",
            "Technical Project Manager",
            "Backend Engineer"
        ];
        for ($i = 0; $i < count($data); $i++) {
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }

        $manager->flush();
    }
}