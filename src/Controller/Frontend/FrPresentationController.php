<?php

namespace App\Controller\Frontend;

use App\Entity\Agenda;
use App\Entity\Historique;
use App\Entity\Portrait;
use App\Entity\Presentation;
use App\Entity\Presse;
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
        }elseif ($slug === 'presse'){
            return $this->render('frontend/presses.html.twig',[
                'presses' => $this->getDoctrine()->getRepository(Presse::class)->findBy([],['publishedAt'=>"DESC"]),
                'agendas' => $agendas,
                'pagination' => false
            ]);
        }elseif ($slug === 'conseil-administration'){
            return $this->render('frontend/cadministration.html.twig',[
                'portraits' => $this->getDoctrine()->getRepository(Portrait::class)->findByInstance('conseil'),
                'agendas' => $agendas,
                'pagination' => false
            ]);
        }elseif ($slug === 'comite-de-pilotage'){
            return $this->render('frontend/copilotage.html.twig',[
                'portraits' => $this->getDoctrine()->getRepository(Portrait::class)->findByInstance('pilotage'),
                'agendas' => $agendas,
                'pagination' => false
            ]);
        }else{
            return $this->render('frontend/presentation.html.twig',[
                'article' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>"DESC"]),
                'agendas' => $agendas
            ]);
        }
    }

    /**
     * @Route("/presse/{slug}", name="frontend_presse_show", methods={"GET"})
     */
    public function presse(Presse $presse)
    {
        return $this->render('frontend/presse.html.twig',[
            'presse' => $presse,
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->getEncours(),
        ]);
    }
}
