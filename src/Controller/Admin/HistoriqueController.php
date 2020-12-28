<?php

namespace App\Controller\Admin;

use App\Entity\Historique;
use App\Form\HistoriqueType;
use App\Repository\HistoriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/historique")
 */
class HistoriqueController extends AbstractController
{
    /**
     * @Route("/", name="backend_historique_index", methods={"GET"})
     */
    public function index(HistoriqueRepository $historiqueRepository): Response
    {
        return $this->render('historique/index.html.twig', [
            'historiques' => $historiqueRepository->findBy([],['annee'=>"DESC"]),
            'show' => "historique"
        ]);
    }

    /**
     * @Route("/new", name="backend_historique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $historique = new Historique();
        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $choix = $request->get('revenir');

            $entityManager->persist($historique);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'historique a bien été ajouté!");

            if ($choix){
                return $this->redirectToRoute('backend_historique_new');
            }

            return $this->redirectToRoute('backend_historique_index');
        }

        return $this->render('historique/new.html.twig', [
            'historique' => $historique,
            'form' => $form->createView(),
            'show' => "historique"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_historique_show", methods={"GET"})
     */
    public function show(Historique $historique): Response
    {
        return $this->render('historique/show.html.twig', [
            'historique' => $historique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_historique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Historique $historique): Response
    {
        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $choix = $request->get('revenir');

            $this->addFlash('success', "<strong>Succes!</strong> L'historique a bien été modifié!");

            if ($choix){
                return $this->redirectToRoute('backend_historique_new');
            }

            return $this->redirectToRoute('backend_historique_index');
        }

        return $this->render('historique/edit.html.twig', [
            'historique' => $historique,
            'form' => $form->createView(),
            'show' => "historique"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_historique_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Historique $historique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$historique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($historique);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'historique a bien été supprimé!");
        }

        return $this->redirectToRoute('backend_historique_index');
    }
}
