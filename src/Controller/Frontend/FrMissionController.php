<?php


namespace App\Controller\Frontend;


use App\Entity\Agenda;
use App\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mission")
 */
class FrMissionController extends AbstractController
{
    /**
     * @Route("/{mission}", name="frontend_mission", methods={"GET","POST"})
     */
    public function show(Mission $mission): Response
    {
        return $this->render('frontend/mission.html.twig',[
            'mission' => $mission,
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->getEncours(),
        ]);
    }
}