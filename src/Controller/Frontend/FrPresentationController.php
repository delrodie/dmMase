<?php

namespace App\Controller\Frontend;

use App\Entity\Agenda;
use App\Entity\Historique;
use App\Entity\Presentation;
use App\Entity\Statut;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/qui-sommes-nous")
 */
class FrPresentationController extends AbstractController
{
    /**
     * @Route("/{slug}", name="frontend_presentation", methods={"GET"})
     */
    public function show($slug)
    {
        $agendas = $this->getDoctrine()->getRepository(Agenda::class)->getEncours();
        if ($slug === 'presentation'){
            return $this->render('frontend/presentation.html.twig',[
                'article' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>"DESC"]),
                'agendas' => $agendas
            ]);
        }elseif ($slug === 'historique'){
            return $this->render('frontend/historique.html.twig',[
                'historiques' => $this->getDoctrine()->getRepository(Historique::class)->findBy([],['annee'=>"ASC"]),
                'agendas' => $agendas
            ]);
        }elseif ($slug === 'statut'){
            return $this->render('frontend/statut.html.twig',[
                'statut' => $this->getDoctrine()->getRepository(Statut::class)->findOneBy([],['id'=>"DESC"]),
                'agendas' => $agendas
            ]);
        }
    }
}