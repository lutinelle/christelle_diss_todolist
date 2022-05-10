<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function readList(ToDoListRepository $toDoListRepository ): Response
    {

        $lists = $toDoListRepository->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'lists'=>$lists
        ]);
    }

    #[Route('/create', name: 'create')]
    public function addList(EntityManagerInterface $em, Request $request,
                             ToDoListRepository $toDoListRepository ): Response
    {
        //create
        $list = new ToDoList();

        $formList = $this->createForm(ToDoListType::class,$list);
        $formList->handleRequest($request);

        if ($formList->isSubmitted() && $formList->isValid()) {
            $em->persist($list);
            $em->flush();
        }

        //read
        $lists = $toDoListRepository->findAll();





        //render
        return $this->render('default/addList.html.twig', [
            'controller_name' => 'modale',
            'formList' => $formList->createView(),
            'lists'=>$lists
        ]);
    }
    #[Route('/update', name: 'update')]
    public function updateList(EntityManagerInterface $em, Request $request,
                            ToDoListRepository $toDoListRepository ): Response
    {

        //read
        $lists = $toDoListRepository->findAll();



        //update
        $listToUpdate = $em->getRepository(ToDoList::class)->find(1);
        $formUpdateList = $this->createForm(ToDoListType::class,$listToUpdate);
        $formUpdateList->handleRequest($request);

        if ($formUpdateList->isSubmitted() && $formUpdateList->isValid()) {
        $em->flush();
        }

        //render
        return $this->render('default/updateList.html.twig', [
            'controller_name' => 'modale',
            'formList' => $formUpdateList->createView(),
            'lists'=>$lists
        ]);
    }

}
