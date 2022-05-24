<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class TaskController extends AbstractController
{

    #[Route('/addTask/{id}', name: 'addTask')]
    public function addTask(ToDoList $list, Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setList($list);
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->renderForm('task/addTask.html.twig', [
            'task' => $task,
            'formTask' => $form,
        ]);
    }


    #[Route('/updateTask/{id}', name: 'updateTask')]
    public function updateTask(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->renderForm('task/updateTask.html.twig', [
            'task' => $task,
            'formTask' => $form,
        ]);
    }

    #[Route('/delTask/{id}', name: 'delTask')]
    public function delTask(int $id, EntityManagerInterface $em): Response
    {
        $TaskToDel = $em->getRepository(Task::class)->find($id);
        $em->remove($TaskToDel);
        $em->flush();

        return $this->redirectToRoute('index');
    }

    #[Route('/updateTaskState/{id}', name: 'updateTaskState')]
    public function updateTaskState( EntityManagerInterface $em, Task $TaskToUpdate): Response
    {
        $TaskToUpdate->setState(!$TaskToUpdate->getState());
        $em->flush();
        return $this->redirectToRoute('index');
    }
}
