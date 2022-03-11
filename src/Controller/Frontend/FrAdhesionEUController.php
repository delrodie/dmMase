<?php

namespace App\Controller\Frontend;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\AgendaRepository;
use App\Repository\EntrepriseRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adhesion/entreprises-utilisatrices")
 */
class FrAdhesionEUController extends AbstractController
{
	private $agendaRepository;
	private $gestionMedia;
	private $entrepriseRepository;
	
	public function __construct(AgendaRepository $agendaRepository, GestionMedia $gestionMedia, EntrepriseRepository $entrepriseRepository)
	{
		$this->agendaRepository = $agendaRepository;
		$this->gestionMedia = $gestionMedia;
		$this->entrepriseRepository = $entrepriseRepository;
	}
	
    /**
     * @Route("/", name="frontend_adhesion_eu_formulaire", methods={"GET","POST"})
     */
    public function index(Request $request): Response
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
		    }else{
			    $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez télécharger votre logo");
			
			    return $this->redirectToRoute('frontend_adhesion_eu_formulaire');
		    }
		
		    //gestion des slug
		    $slugify = new Slugify();
		    $slug = $slugify->slugify($entreprise->getRaisonSociale());
		    $entreprise->setSlug($slug);
			
			$entreprise->setType('EU');
			
		    $entityManager->persist($entreprise);
		    $entityManager->flush();
		
		    return $this->redirectToRoute('frontend_adhesion_eu_telechargement',[
				'slug' => $entreprise->getSlug()
		    ]);
	    }
		
        return $this->render('fr_adhesion_eu/index.html.twig', [
            'agendas' => $this->agendaRepository->getEncours(),
	        'form' => $form->createView(),
        ]);
    }
	
	/**
	 * @Route("/{slug}", name="frontend_adhesion_eu_telechargement", methods={"GET"})
	 */
	public function telechargement(Entreprise $entreprise)
	{
		return $this->render('fr_adhesion_eu/telechargement.html.twig',[
			'agendas' => $this->agendaRepository->getEncours(),
			'entreprise' => $entreprise
		]);
	}
}
