<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassroomRepository;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Classromm::class);
        $form = $repository->findAll();
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
            'form' => $form
        ]);
    }

    #[Route('/read', name: 'read')]
    public function Read(ClassroomRepository $ClassroomRepository):Response
    {
        $list = $ClassroomRepository-> findAll() ;
        return $this->render('classroom/list.html.twig',[
            'list' => $list
        ]);
    }
    #[Route('/add', name: 'add')]
    public function Add(Request $request):Response
    {
        $classroom=new Classroom();
        $form=$this->createFormBuilder($classroom)
        ->add('name', TextType::class, array('attr'=> array('class'=>'form-control'),
        ))
        ->add('save', SubmitType::class, array('label'=>'Ajouter'))
        ->getForm();
        $form->handleRequest($request);      
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($classroom);
            $entityManager->flush();
            return $this->redirectToRoute('read');
 
        }

        $classroomRepository = $this->getDoctrine()->getRepository(Classroom::class);
        $list=$classroomRepository->findAll();
            
        return $this->render('classroom/ajoutClassroom.html.twig', [
            'form' => $form->createView(),
            'list' => $list
        ]);  
       
    }
    

    #[Route('/delete/{id}', name: 'delete')]
    public function Delete($id):Response
    {
        $em=$this->getDoctrine()->getManager();
        $classroom=$em->getRepository(Classroom::class)->find($id);
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute('read');
    }
    #[Route('/edit/{id}', name: 'edit')]
    public function Edit(ClassroomRepository $classroomRepository,Request $request, $id):Response
    {
        $classroom = $classroomRepository->find($id);

        $form=$this->createFormBuilder($classroom)
        ->add('name', TextType::class, array('attr'=> array('class'=>'form-control'),
        ))
        ->add('save', SubmitType::class, array('label'=>'Modifier'))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        
            $objectManager = $this->getDoctrine()->getManager();
            $classromm = $objectManager->getRepository(Classroom::class)->find($id);
            $objectManager->flush();
            $this->addFlash('info', 'You have updated a task!');
            return $this->redirectToRoute('read');

           
        }
        $classroomRepository = $this->getDoctrine()->getRepository(Classroom::class);
        $list=$classroomRepository->findAll();

        return $this->render('classroom/modifier.html.twig',array(
            'form'=>$form->createView(),
            'list' => $list
        ));
    }
}
