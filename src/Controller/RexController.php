<?php

namespace App\Controller;

use App\Entity\Rex;
use App\Form\RexType;
use App\Repository\RexRepository;
use App\Utilities\GestionMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/rex")
 */
class RexController extends AbstractController
{
    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_rex_index", methods={"GET"})
     */
    public function index(RexRepository $rexRepository): Response
    {
        return $this->render('rex/index.html.twig', [
            'rexes' => $rexRepository->findBy([],['id'=>"DESC"]),
            'show' => "rex"
        ]);
    }

    /**
     * @Route("/new", name="backend_rex_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rex = new Rex();
        $form = $this->createForm(RexType::class, $rex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Gestion des medias
            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'rex');

                $rex->setMedia($media);
            }else{
                $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez telecharger une photo");

                return $this->redirectToRoute('backend_agenda_new');
            }

            $entityManager->persist($rex);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> le message a bien été enregistré!");

            return $this->redirectToRoute('backend_rex_index');
        }

        return $this->render('rex/new.html.twig', [
            'rex' => $rex,
            'form' => $form->createView(),
            'show' => "rex"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_rex_show", methods={"GET"})
     */
    public function show(Rex $rex): Response
    {
        return $this->render('rex/show.html.twig', [
            'rex' => $rex,
            'show' => "rex"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_rex_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rex $rex): Response
    {
        $form = $this->createForm(RexType::class, $rex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'rex');

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($rex->getMedia(), 'rex');

                $rex->setMedia($media);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "<strong>Succes!</strong> le message a bien été modifié!");

            return $this->redirectToRoute('backend_rex_index');
        }

        return $this->render('rex/edit.html.twig', [
            'rex' => $rex,
            'form' => $form->createView(),
            'show' => "rex"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_rex_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rex $rex): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rex->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $rex->getMedia();
            $entityManager->remove($rex);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($media, 'rex');

            $this->addFlash('success', "<strong>Succes!</strong> le message a bien été supprimé!");
        }

        return $this->redirectToRoute('backend_rex_index');
    }
}
