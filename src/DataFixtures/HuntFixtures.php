<?php

namespace App\DataFixtures;

use App\Entity\Hunt;
use App\Entity\Target;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HuntFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++){
            $target = new Target();
            $target->setName("Michel-0".$i);
            $target->setDescription("Super violent");
            $hunt = new Hunt();
            $hunt->setName("Wanted Notice ".$i);
            $hunt->setBounty(rand(100,2000000));
            $hunt->setTarget($target);
            $manager->persist($hunt);
        }

        $manager->flush();
    }
}