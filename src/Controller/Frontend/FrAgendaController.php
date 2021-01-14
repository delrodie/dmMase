<?php


namespace App\Controller\Frontend;

use App\Entity\Actualite;
use App\Entity\Agenda;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agenda")
 */
class FrAgendaController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/", name="frontend_agenda_list", methods={"GET"})
     */
    public function liste()
    {
        return $this->render("frontend/agendas.html.twig",[
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->findBy([],['dateFin'=>"DESC"]),
            'actualites' => $this->getDoctrine()->getRepository(Actualite::class)->findBy([],['id'=>"DESC"]),
            'pagination' => false
        ]);
    }

    /**
     * @Route("/{slug}", name="frontend_agenda_show", methods={"GET","POST"})
     */
    public function show(Agenda $agenda)
    {
        return $this->render("frontend/agenda.html.twig",[
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->getEncours(),
            'agenda' => $agenda
        ]);
    }
}