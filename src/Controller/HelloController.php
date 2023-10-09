<?php 

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HelloController extends AbstractController
{
    private array $messages =
    [
        ['message' => 'Hello', 'created' => '2023/09/12'],
        ['message' => 'Hi', 'created' => '2023/08/12'],
        ['message' => 'Bye', 'created' => '2022/07/12']
    ];
    
    #[Route('/', name: 'app_index')]
    public function index(MicroPostRepository $posts, CommentRepository $comments): Response
    {
//       $post = new MicroPost();
//       $post->setTitle('hello');
//       $post->setText('23456789');        
//       $post->setCreated(new DateTime());

        $post = $posts->find(19);
        $comment = $post->getComments()[0];

        $post->removeComment($comment);
        $posts->add($post, true);

//        dd($post);
//      $profiles->add($profile, true);

//        $posts->add($post, true);
        
        return $this->render(
            'micro_post/index.html.twig', 
            [
                'messages' => $this->messages,
                'limit' => 3
            ]
        );
    } 

    #[Route('/messages/{id<\d+>}', 'app_show_one')]
    public function showOne(int $id): Response
    {
        return $this->render(
            //twig relatív útvonala, alap config(templatek keresésének helye?): config/packages/twig.yaml
            'hello/show_one.html.twig',
            [
                //változó átadása a twignek
                'message' => $this->messages[$id]
            ]
        );
      //  return new Response($this->messages[$id]);
    }
}