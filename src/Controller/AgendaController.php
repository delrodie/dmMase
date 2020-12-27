<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use App\Repository\AgendaRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/agenda")
 */
class AgendaController extends AbstractController
{
    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_agenda_index", methods={"GET"})
     */
    public function index(AgendaRepository $agendaRepository): Response
    {
        return $this->render('agenda/index.html.twig', [
            'agendas' => $agendaRepository->findBy([],['dateDebut'=>"DESC"]),
            'show' => "agenda"
        ]);
    }

    /**
     * @Route("/new", name="backend_agenda_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $agenda = new Agenda();
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Gestion des medias
            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'agenda');

                $agenda->setMedia($media);
            }else{
                $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez telecharger une photo");

                return $this->redirectToRoute('backend_agenda_new');
            }

            //gestion des slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($agenda->getTitre().'-'.time());
            $agenda->setSlug($slug);

            $entityManager->persist($agenda);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'agenda a bien été enregistré!");

            return $this->redirectToRoute('backend_agenda_index');
        }

        return $this->render('agenda/new.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
            'show' => "agenda"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_agenda_show", methods={"GET"})
     */
    public function show(Agenda $agenda): Response
    {
        return $this->render('agenda/show.html.twig', [
            'agenda' => $agenda,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="backend_agenda_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Agenda $agenda): Response
    {
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'agenda');

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($agenda->getMedia(), 'agenda');

                $agenda->setMedia($media);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'agenda a bien été modifié!");

            return $this->redirectToRoute('backend_agenda_index');
        }

        return $this->render('agenda/edit.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
            'show' => 'agenda'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_agenda_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Agenda $agenda): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agenda->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $agenda->getMedia();
            $entityManager->remove($agenda);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($media, 'agenda');
            
            $this->addFlash('success', "<strong>Succes!</strong> L'agenda a bien été supprimé!");
        }

        return $this->redirectToRoute('backend_agenda_index');
    }
}
