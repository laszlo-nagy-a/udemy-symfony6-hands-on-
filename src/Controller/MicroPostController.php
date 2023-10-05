<?php

namespace App\Controller;

use DateTime;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        /*
        $microPost = new MicroPost();
        $microPost->setTitle('It comes from a controller!');
        $microPost->setText('Hi!');
        $microPost->setCreated(new DateTime());

        //$posts->add($microPost, true);

        $sixthPost = $posts->find(6);
        $posts->remove($sixthPost, true);
        */
        return $this->render('micro_post/index.html.twig', 
        [
            'posts' => $posts->findAll()
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
            'controller_name' => 'MicroPostController',
        ]);
    }
    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, MicroPostRepository $posts): Response
    {
        $microPost = new MicroPost();
        // elkészíti a formot entitás mezőire fordítva
        $form = $this->createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            ->add('submit', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        // ez elkapja a post method adatait?
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // form adatokat kiveszi és beteszi adatbzásiba az entitást
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $posts->add($post, true);

            // üzenet adás
            $this->addFlash('success', 'Your micro post have been added!');

            return $this->redirectToRoute('app_micro_post');
        }
        return $this->renderForm('micro_post/add.html.twig',
            [
                'form' => $form
            ]);
    }
    
}
