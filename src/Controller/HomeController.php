<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Agenda;
use App\Entity\Faq;
use App\Entity\Mission;
use App\Entity\Rex;
use App\Entity\Slider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        //$misssion = $this->getDoctrine()->getRepository(Faq::class)->findBy([],['id'=>"DESC"]); dd($misssion );
        return $this->render('home/index.html.twig', [
            'sliders' => $this->getDoctrine()->getRepository(Slider::class)->findBy(['statut'=>true],['id'=>'DESC']),
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->getEncours(),
            'mission' => $this->getDoctrine()->getRepository(Mission::class)->findOneBy([],['id'=>"DESC"]),
            'actualites' => $this->getDoctrine()->getRepository(Actualite::class)->findBy([],['id'=>"DESC"]),
            'faqs' => $this->getDoctrine()->getRepository(Faq::class)->findAll(),
            'rexes' => $this->getDoctrine()->getRepository(Rex::class)->findBy([],['id'=>"DESC"]),
            'entreprise' => false
        ]);
    }
}
