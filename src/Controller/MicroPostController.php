<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {    
        return $this->render('micro_post/index.html.twig', 
        [
            'posts' => $posts->findAllWithComments()
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
            'controller_name' => 'MicroPostController',
        ]);
    }
    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    // jogosultság kezelése
    #[IsGranted('ROLE_WRITER')]
    public function add(Request $request, MicroPostRepository $posts): Response
    {
        // elkészíti a formot entitás mezőire fordítva
        $form = $this->createForm(MicroPostType::class, new MicroPost());

        // TODO: megnézni ez pontosan mit csinál
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // form adatokat kiveszi és beteszi adatbzásiba az entitást
            $post = $form->getData();
            $post->setAuthor($this->getUser());
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

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response
    {      
        $form = $this->createForm(MicroPostType::class, $post);
        // ez elkapja a post method adatait?
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();
            $posts->add($post, true);

            // üzenet adás
            $this->addFlash('success', 'Your micro post have been updated!');

            return $this->redirectToRoute('app_micro_post');
        }
        return $this->renderForm('micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]);
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComent(MicroPost $post, Request $request, CommentRepository $comments): Response
    {      
        $form = $this->createForm(CommentType::class, new Comment());
    
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $comments->add($comment, true);

            // üzenet adás
            $this->addFlash('success', 'Your comment have been updated!');

            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
        );
        }
        return $this->renderForm('micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]);
    }    
}
