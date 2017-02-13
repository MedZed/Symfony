<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class TodoController extends Controller
{
    /**
     * @Route("/", name="todo_list")
     */
    public function listAction(Request $request)
    {
        $todos = $this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();
        // replace this example code with whatever you need

        return $this->render('todo/index.html.twig',array(
            'todos' => $todos));
    }
    /**
     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo = new todo;
        $form = $this->createFormBuilder($todo)
        ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('catagory',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('priority',ChoiceType::class,array('choices'=>array('Low'=>'Low','Normal'=>'Normal','High'=>'High'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('due_date',DateTimeType::class,array('attr'=>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))
        
        ->add('save',SubmitType::class,array('label'=>'Create Todo','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))

        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Get data ..
            $name=$form['name']->getData();
            $category=$form['catagory']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $due_date=$form['due_date']->getData();
            $now = new\DateTime('now');

            //Set data..
            $todo->setName($name);
            $todo->setCatagory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();

            $em->persist($todo);
            $em->flush();

            $this->addFlash(
                'notice',
                'Todo Added'
                );
            return $this->redirectToRoute('todo_list');


        }

        // replace this example code with whatever you need
        return $this->render('todo/create.html.twig',array('form' => $form->createView() ));
    }



    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request)
    {

        $todo = $this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
            
            $todo->setName($todo->getName());
            $todo->setCatagory($todo->getCatagory());
            $todo->setDescription($todo->getDescription());
            $todo->setPriority($todo->getPriority());
            $todo->setDueDate($todo->getDueDate());
            $now = new\DateTime('now');
            $todo->setCreateDate($now);

        $form = $this->createFormBuilder($todo)
        ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('catagory',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('priority',ChoiceType::class,array('choices'=>array('Low'=>'Low','Normal'=>'Normal','High'=>'High'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('due_date',DateTimeType::class,array('attr'=>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))

        ->add('save',SubmitType::class,array('label'=>'Update Todo','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))

        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Get data ..
            $name=$form['name']->getData();
            $category=$form['catagory']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $due_date=$form['due_date']->getData();
            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo')->find($id);

            //Set data..
            $todo->setName($name);
            $todo->setCatagory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);




            $em->flush();

            $this->addFlash(
                'notice',
                'Todo Updated'
                );
            return $this->redirectToRoute('todo_list');
        }
        // replace this example code with whatever you need

        return $this->render('todo/edit.html.twig',array(
            'todo' => $todo,'form'=>$form->createView()));
        
        
    }

    /**
     * @Route("/todo/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
        $todo = $this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
        // replace this example code with whatever you need

        return $this->render('todo/details.html.twig',array(
            'todo' => $todo));
    }

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo')->find($id);
            $em->remove($todo);
            $em->flush();
            $this->addFlash(
                'delete',
                'Todo Removerd');
             return $this->redirectToRoute('todo_list');


    }

        /**
     * @Route("/todo/about", name="todo_about")
     */
    public function aboutAction($id)
    {
            return $this->render('todo/about.html.twig');

    }
    

}
 