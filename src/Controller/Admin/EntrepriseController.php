<?php

namespace App\Controller\Admin;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/entreprise")
 */
class EntrepriseController extends AbstractController
{
	private $gestionMedia;
	
	public function __construct(GestionMedia $gestionMedia)
	{
		$this->gestionMedia = $gestionMedia;
	}
	
    /**
     * @Route("/", name="backend_entreprise_index", methods={"GET"})
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entrepriseRepository->findAll(),
	        'show' => 'entreprise'
        ]);
    }

    /**
     * @Route("/new", name="backend_entreprise_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
	
	        // Gestion des medias
	        $mediaFile = $form->get('logo')->getData();
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'entreprise');
		
		        $entreprise->setLogo($media);
	        }
	
	        //gestion des slug
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($entreprise->getRaisonSociale());
	        $entreprise->setSlug($slug);
			
            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('backend_entreprise_index');
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
	        'show' => 'entreprise'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_entreprise_show", methods={"GET"})
     */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_entreprise_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entreprise $entreprise): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        // Gestion des medias
	        $mediaFile = $form->get('logo')->getData();
	
	        if ($mediaFile){
		        $media = $this->gestionMedia->upload($mediaFile, 'entreprise');
		
		        // Supression de l'ancien fichier
		        if ($entreprise->getLogo())
			        $this->gestionMedia->removeUpload($entreprise->getLogo(), 'entreprise');
		
		        $entreprise->setLogo($media);
	        }
			
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_entreprise_index');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
	        'show' => 'entreprise'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_entreprise_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entreprise $entreprise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entreprise);
            $entityManager->flush();
	
	        // Supression de l'ancien fichier
	        if ($entreprise->getLogo())
		        $this->gestionMedia->removeUpload($entreprise->getLogo(), 'entreprise');
        }

        return $this->redirectToRoute('backend_entreprise_index');
    }
}
