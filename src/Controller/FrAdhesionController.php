<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adhesion')]
class FrAdhesionController extends AbstractController
{
    #[Route('/', name: 'frontend_adhesion')]
    public function index(): Response
    {
        return $this->render('fr_adhesion/index.html.twig', [
            'controller_name' => 'FrAdhesionController',
        ]);
    }
}
