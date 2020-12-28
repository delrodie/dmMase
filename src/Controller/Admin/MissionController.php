<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use App\Utilities\GestionMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/mission")
 */
class MissionController extends AbstractController
{
    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_mission_index", methods={"GET"})
     */
    public function index(MissionRepository $missionRepository): Response
    {
        $exit = $missionRepository->findOneBy([],['id'=>"DESC"]);

        if (!$exit) return  $this->redirectToRoute('backend_mission_new');
        else return $this->redirectToRoute('backend_mission_show', ['id'=>$exit->getId()]);

    }

    /**
     * @Route("/new", name="backend_mission_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'mission');

                $mission->setMedia($media);
            }else{
                $this->addFlash('danger', "<strong>Erreur!</strong> Vous devez telecharger une photo");

                return $this->redirectToRoute('backend_mission_new');
            }

            $entityManager->persist($mission);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Success!</strong> La mission a bien été enregistrée");

            return $this->redirectToRoute('backend_mission_show', ['id'=>$mission->getId()]);
        }

        return $this->render('mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
            'show' => "mission"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_mission_show", methods={"GET"})
     */
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', [
            'mission' => $mission,
            'show' => "mission"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_mission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mission $mission): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mediaFile = $form->get('media')->getData();

            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'mission');

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($mission->getMedia(), 'mission');

                $mission->setMedia($media);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "<strong>Success!</strong> La mission a bien été modifiée");

            return $this->redirectToRoute('backend_mission_index');
        }

        return $this->render('mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
            'show' => "mission"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_mission_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mission $mission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $mission->getMedia();
            $entityManager->remove($mission);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($file, 'mission');

            $this->addFlash('success', "<strong>Success!</strong> La mission a bien été suprimée");
        }

        return $this->redirectToRoute('backend_mission_index');
    }
}
