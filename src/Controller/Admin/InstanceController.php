<?php

namespace App\Controller\Admin;

use App\Entity\Instance;
use App\Form\InstanceType;
use App\Repository\InstanceRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/instance")
 */
class InstanceController extends AbstractController
{
    /**
     * @Route("/", name="backend_instance_index", methods={"GET"})
     */
    public function index(InstanceRepository $instanceRepository): Response
    {
        return $this->render('instance/index.html.twig', [
            'instances' => $instanceRepository->findAll(),
	        'show' => 'instance'
        ]);
    }

    /**
     * @Route("/new", name="backend_instance_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
			
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($instance->getLibelle());
	        $instance->setSlug($slug);
			
            $entityManager->persist($instance);
            $entityManager->flush();
	
	        $this->addFlash('success', "<strong>Succes!</strong> L'instance a bien été enregistrée!");
	
	        return $this->redirectToRoute('backend_instance_index');
        }

        return $this->render('instance/new.html.twig', [
            'instance' => $instance,
            'form' => $form->createView(),
	        'show' => 'instance'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_instance_show", methods={"GET"})
     */
    public function show(Instance $instance): Response
    {
        return $this->render('instance/show.html.twig', [
            'instance' => $instance,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_instance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Instance $instance): Response
    {
        $form = $this->createForm(InstanceType::class, $instance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	
	        $slugify = new Slugify();
	        $slug = $slugify->slugify($instance->getLibelle());
	        $instance->setSlug($slug);
			
            $this->getDoctrine()->getManager()->flush();
	
	        $this->addFlash('success', "<strong>Succes!</strong> L'instance a bien été modifiée!");
	
	
	        return $this->redirectToRoute('backend_instance_index');
        }

        return $this->render('instance/edit.html.twig', [
            'instance' => $instance,
            'form' => $form->createView(),
	        'show' => 'instance'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_instance_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Instance $instance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($instance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_instance_index');
    }
}
