<?php

namespace App\Controller\Frontend;

use App\Entity\Agenda;
use App\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/document")
 */
class FrDocumentController extends AbstractController
{
    /**
     * @Route("/", name="frontend_document_liste")
     */
    public function liste()
    {
        return $this->render('frontend/documents.html.twig',[
            'documents' => $this->getDoctrine()->getRepository(Document::class)->findBy([],['id'=>"DESC"]),
            'agendas' => $this->getDoctrine()->getRepository(Agenda::class)->findBy([],['dateFin'=>"DESC"]),
            'pagination' => false
        ]);
    }

    /**
     * @Route("/{slug}", name="frontend_document_show", methods={"GET"})
     */
    public function show(Document $document)
    {
        return $this->render('frontend/document.html.twig',[
            'document' => $document
        ]);
    }
}