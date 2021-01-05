<?php

namespace App\Controller;

use App\Entity\Presse;
use App\Form\PresseType;
use App\Repository\PresseRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/presse")
 */
class PresseController extends AbstractController
{
    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_presse_index", methods={"GET"})
     */
    public function index(PresseRepository $presseRepository): Response
    {
        return $this->render('presse/index.html.twig', [
            'presses' => $presseRepository->findBy([],['publishedAt'=>"DESC"]),
            'show' => "presse"
        ]);
    }

    /**
     * @Route("/new", name="backend_presse_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $presse = new Presse();
        $form = $this->createForm(PresseType::class, $presse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // Gestion des medias
            $mediaFile = $form->get('media')->getData();

            if (!$mediaFile && !$presse->getLien()){

                $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez ajouter soit la capture presse ou le lien de l'article");

                return $this->redirectToRoute('backend_presse_new');
            }

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'presse');

                $presse->setMedia($media);
            }

            //gestion des slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($presse->getTitre().'-'.time());
            $presse->setSlug($slug);

            $entityManager->persist($presse);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'article presse a bien été enregistré!");

            return $this->redirectToRoute('backend_presse_index');
        }

        return $this->render('presse/new.html.twig', [
            'presse' => $presse,
            'form' => $form->createView(),
            'show' => 'presse'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_presse_show", methods={"GET"})
     */
    public function show(Presse $presse): Response
    {
        return $this->render('presse/show.html.twig', [
            'presse' => $presse,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="backend_presse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Presse $presse): Response
    {
        $form = $this->createForm(PresseType::class, $presse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $mediaFile = $form->get('media')->getData();

            if (!$mediaFile && !$presse->getLien()){

                $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez ajouter soit la capture presse ou le lien de l'article");

                return $this->redirectToRoute('backend_presse_edit', ['slug'=>$presse->getSlug()]);
            }

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'presse');

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($presse->getMedia(), 'presse');

                $presse->setMedia($media);
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'article presse a bien été modifié!");

            return $this->redirectToRoute('backend_presse_index');
        }

        return $this->render('presse/edit.html.twig', [
            'presse' => $presse,
            'form' => $form->createView(),
            'show' => 'presse'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_presse_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Presse $presse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($presse->getMedia())
                $media = $presse->getMedia();

            $entityManager->remove($presse);
            $entityManager->flush();

            // Supression de l'ancien fichier
            if ($presse->getMedia())
                $this->gestionMedia->removeUpload($media, 'presse');

            $this->addFlash('success', "<strong>Succes!</strong> L'article presse a bien été supprimé!");
        }

        return $this->redirectToRoute('backend_presse_index');
    }
}
