<?php

namespace App\Controller\Admin;

use App\Entity\Portrait;
use App\Form\PortraitType;
use App\Repository\PortraitRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/portrait")
 */
class PortraitController extends AbstractController
{
	private $gestionMedia;
	
	public function __construct(GestionMedia $gestionMedia)
	{
		$this->gestionMedia = $gestionMedia;
	}
	
    /**
     * @Route("/", name="backend_portrait_index", methods={"GET"})
     */
    public function index(PortraitRepository $portraitRepository): Response
    {
        return $this->render('portrait/index.html.twig', [
            'portraits' => $portraitRepository->findAll(),
	        'show' => 'portrait'
        ]);
    }

    /**
     * @Route("/new", name="backend_portrait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $portrait = new Portrait();
        $form = $this->createForm(PortraitType::class, $portrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
	
	        // Gestion des medias
	        $mediaFile = $form->get('media')->getData();
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'portrait');
		
		        $portrait->setMedia($media);
	        }
	
	        //gestion des slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($portrait->getNom().'-'.$portrait->getPrenoms());
	        $portrait->setSlug($slug);
            $entityManager->persist($portrait);
            $entityManager->flush();

            return $this->redirectToRoute('backend_portrait_index');
        }

        return $this->render('portrait/new.html.twig', [
            'portrait' => $portrait,
            'form' => $form->createView(),
	        'show' => 'portrait'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_portrait_show", methods={"GET"})
     */
    public function show(Portrait $portrait): Response
    {
        return $this->render('portrait/show.html.twig', [
            'portrait' => $portrait,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="backend_portrait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Portrait $portrait): Response
    {
        $form = $this->createForm(PortraitType::class, $portrait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        // Gestion des medias
	        $mediaFile = $form->get('media')->getData();
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'portrait');
		
		        // Supression de l'ancien fichier
		        if ($portrait->getMedia())
		            $this->gestionMedia->removeUpload($portrait->getMedia(), 'portrait');
		
		        $portrait->setMedia($media);
	        }
	
	        //gestion des slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($portrait->getNom().'-'.$portrait->getPrenoms());
	        $portrait->setSlug($slug);
			
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_portrait_index');
        }

        return $this->render('portrait/edit.html.twig', [
            'portrait' => $portrait,
            'form' => $form->createView(),
	        'show' => 'portrait'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_portrait_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Portrait $portrait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portrait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portrait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_portrait_index');
    }
}
