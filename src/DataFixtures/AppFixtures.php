<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\UserRepository;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> userRepository = $entityManager -> getRepository(User::class);
    }

    public function userList(): ? User
    {
        $users = $this -> userRepository ->findAll();
        //var_dump($users);
        return $users[0];
    }

    public function load(ObjectManager $manager): void
    {
        $myUserList = $this -> userList();
        $category = new Category();
        $category->setName('Boring');
        $manager->persist($category);
        for ($i=0;$i<10; $i++){
            $task = new Task();
            $task -> setTitle('Task number'.$i);
            $task-> setPriority(1);
            //$task-> addUser($myUserList);
            $task -> setDescription('Task description');
            $task -> setDone(false);
            $manager -> persist($task);
            //var_dump($task->getUserTasks()[0]->getName());
        }
        $manager->flush();
        //$task->getUserTasks()[0]->addTask($task);

        // $product = new Product();
        // $manager->persist($product);


    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
