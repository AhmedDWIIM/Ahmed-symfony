<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/tasks/create", name="create_task")
     */
    public function createTask(Request $request) : Response {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->taskRepository->save($task);

            return $this->redirectToRoute('tasks_list');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form
        ]);
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
    /**
     * @Route("/tasks/{id}/update", name="update_task")
     */
    public function updateTask(int $id, Request $request) : Response {

        $task = $this-> taskRepository ->find($id);
        if (null === $task) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->taskRepository->save($task);

            return $this->redirectToRoute('tasks_list');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form
        ]);
    }
    /**
     * @Route("/tasks/{id}/delete", name="delete_task")
     */
    public function deleteTask(int $id): Response
    {
        $task = $this-> taskRepository ->find($id);
        if (null === $task) {
            throw new NotFoundHttpException();
        }
        $this-> taskRepository ->delete($task);
        return $this->redirectToRoute('tasks_list');
    }

}
