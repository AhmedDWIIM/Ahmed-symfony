<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TaskRepository;

class TaskController extends AbstractController
{
    /** @var TaskRepository */
    private $taskRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> taskRepository = $entityManager -> getRepository(Task::class);
    }

    /**
     * @Route("/tasks", name="tasks_list")
     */
    public function tasksList(): Response
    {
        $tasks = $this -> taskRepository ->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    /**
     * @Route("/tasks/{id}", name="task_details")
     * @param int $id
     * @return Response
     */
    public function taskDetails(int $id): Response
    {
        $task = $this-> taskRepository ->find($id);

        if (null === $task) {
            throw new NotFoundHttpException();
        }

        return $this->render('task/details.html.twig', [
            'task' => $task
        ]);
    }
}
