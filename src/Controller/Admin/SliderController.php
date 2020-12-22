<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use App\Utilities\GestionMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/slider")
 */
class SliderController extends AbstractController
{
    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_slider_index", methods={"GET"})
     */
    public function index(SliderRepository $sliderRepository): Response
    {
        return $this->render('slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
            'show' => "slider"
        ]);
    }

    /**
     * @Route("/new", name="backend_slider_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'slide');

                $slider->setMedia($media);
            }else{
                $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez telecharger une photo");

                return $this->redirectToRoute('backend_slider_new');
            }

            $entityManager->persist($slider);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> Le slide a bien été enregistré!");

            return $this->redirectToRoute('backend_slider_index');
        }

        return $this->render('slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
            'show' => "slider"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_slider_show", methods={"GET"})
     */
    public function show(Slider $slider): Response
    {
        return $this->render('slider/show.html.twig', [
            'slider' => $slider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_slider_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Slider $slider): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'slide');

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($slider->getMedia(), 'slide');

                $slider->setMedia($media);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_slider_index');
        }

        return $this->render('slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
            'show' => "slider"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_slider_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Slider $slider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($slider);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> Le slide a bien été enregistré!");
        }

        return $this->redirectToRoute('backend_slider_index');
    }
}
