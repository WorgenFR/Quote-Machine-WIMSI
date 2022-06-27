<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{

    #[Route('/', name:'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('quote_index');
    }

    #[Route('/hello', name:'hello')]
    public function hello(): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => 'World',
        ]);
    }

    #[Route('/hello/{name}', name:'hello_world')]
    public function helloWorld($name): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
