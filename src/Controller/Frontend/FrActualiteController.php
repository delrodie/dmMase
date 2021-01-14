<?php

namespace App\Controller\Frontend;

use App\Entity\Actualite;
use App\Entity\Agenda;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actualite")
 */
class FrActualiteController extends AbstractController
{
    /**
     * @Route("/", name="frontend_actualite_liste")
     */
    public function liste()
    {
        return $this->render('frontend/actualites.html.twig',[
            'actualites' => $this->getDoctrine()->getRepository(Actualite::class)->findBy([],['id'=>"DESC"]),
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->findBy([],['dateFin'=>"DESC"]),
            'pagination' => false
        ]);
    }
    
    /**
     * @Route("/{slug}", name="frontend_actualite_show", methods={"GET"})
     */
    public function show(Actualite $actualite)
    {
        return $this->render('frontend/actualite.html.twig',[
            'actualite' => $actualite,
            'actualites' => $this->getDoctrine()->getRepository(Actualite::class)->findBy([],['id'=>"DESC"]),
        ]);
    }
}