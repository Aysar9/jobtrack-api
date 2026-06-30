<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Enum\ApplicationStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['UNIPLUS Software GmbH', 'PHP/Symfony Developer', 'Münster', 45000, ApplicationStatus::INTERVIEW, '2026-06-10'],
            ['ZOXS GmbH', 'Backend Developer', 'Goch', 44000, ApplicationStatus::APPLIED, '2026-06-18'],
            ['EWU Software GmbH', 'Fullstack PHP Developer', 'Remote', 46000, ApplicationStatus::APPLIED, '2026-06-20'],
            ['Bauwelt Glas- und Stahlprodukte', 'Softwareentwickler', 'Osnabrück', 43000, ApplicationStatus::REJECTED, '2026-05-28'],
        ];

        foreach ($data as [$company, $position, $location, $salary, $status, $appliedAt]) {
            $application = new Application();
            $application->setCompany($company);
            $application->setPosition($position);
            $application->setLocation($location);
            $application->setSalaryExpectation($salary);
            $application->setStatus($status);
            $application->setAppliedAt(new \DateTimeImmutable($appliedAt));
            $application->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($application);
        }

        $manager->flush();
    }
}
