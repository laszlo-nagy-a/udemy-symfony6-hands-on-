<?php 

namespace App\Controller;

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
    
    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit): Response
    {
        return $this->render(
            'micro_post/index.html.twig', 
            [
                'messages' => $this->messages,
                'limit' => $limit
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