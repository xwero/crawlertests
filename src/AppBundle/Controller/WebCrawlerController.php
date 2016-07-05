<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class WebCrawlerController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:WebCrawler:index.html.twig');
    }

    /**
     * @Route("/task")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function taskAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new Task();
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('task_success');
        }

        return $this->render('AppBundle:WebCrawler:task.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/task/success", name="task_success")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function taskSuccessAction(Request $request)
    {
        return $this->render('AppBundle:WebCrawler:taskSuccess.html.twig');
    }

}
