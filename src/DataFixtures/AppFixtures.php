<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Boring');
        $manager->persist($category);
        for ($i=0;$i<10; $i++){
            $task = new Task();
            $task -> setTitle('Task number'.$i);
            $task -> setDescription('Task description');
            $task -> setDone(false);
            $task->setCategory($category);
            $manager -> persist($task);
        }
        $manager->flush();

        // $product = new Product();
        // $manager->persist($product);


    }
}
