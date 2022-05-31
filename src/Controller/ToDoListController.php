<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\TaskRepository;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function readList(ToDoListRepository $toDoListRepository, TaskRepository $taskRepository ) : Response
    {

        $lists = $toDoListRepository->findAll();
        $checked = $toDoListRepository->findToDOListWithValidTask();


        return $this->render('to_do_list/index.html.twig', [
            'controller_name' => 'DefaultController',
            'lists'=>$lists,
            'checked'=>$checked

        ]);
    }

    #[Route('/addList', name: 'addList')]
    public function addList(EntityManagerInterface $em, Request $request,): Response
    {
        //create
        $list = new ToDoList();
        $formList = $this->createForm(ToDoListType::class,$list);
        $formList->handleRequest($request);

        // submit
        if ($formList->isSubmitted() && $formList->isValid()) {
            $em->persist($list);
            $em->flush();
            return $this->redirectToRoute('index');
        }

        //render
        return $this->render('to_do_list/addList.html.twig', [
            'controller_name' => 'modale',
            'formList' => $formList->createView(),
        ]);
    }

    #[Route('/updateList/{id}', name: 'updateList')]
    public function updateList(int $id, EntityManagerInterface $em, Request $request,): Response
    {

        //update
        $listToUpdate = $em->getRepository(ToDoList::class)->find($id);
        $formUpdateList = $this->createForm(ToDoListType::class,$listToUpdate);
        $formUpdateList->handleRequest($request);

        //submit
        if ($formUpdateList->isSubmitted() && $formUpdateList->isValid()) {
            $em->flush();
            return $this->redirectToRoute('index');
        }

        //render
        return $this->render('to_do_list/updateList.html.twig', [
            'controller_name' => 'modale',
            'formList' => $formUpdateList->createView()
        ]);
    }

    #[Route('/delList/{id}', name: 'delList')]
    public function delList(int $id, EntityManagerInterface $em): Response
    {

        //delete
        $listToDel = $em->getRepository(ToDoList::class)->find($id);
            $em->remove($listToDel);
            $em->flush();

            return $this->redirectToRoute('index');

    }
}
