<?php

namespace App\DataFixtures;

use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $wallet1 = new Wallet();
        $wallet1->setUserId(1);
        $wallet1->setAmount(5000);
        $manager->persist($wallet1);

        $wallet2 = new Wallet();
        $wallet2->setUserId(2);
        $wallet2->setAmount(0);
        $manager->persist($wallet2);

        $wallet3 = new Wallet();
        $wallet3->setUserId(3);
        $wallet3->setAmount(100);
        $manager->persist($wallet3);

        $manager->flush();
    }
}
