<?php

namespace App\Controller\Admin;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\StatutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/statut")
 */
class StatutController extends AbstractController
{
    /**
     * @Route("/", name="backend_statut_index", methods={"GET"})
     */
    public function index(StatutRepository $statutRepository): Response
    {
        $exist = $statutRepository->findOneBy([],['id'=>"DESC"]);

        if (!$exist)
            return $this->redirectToRoute('backend_statut_new');
        else
            return $this->redirectToRoute('backend_statut_show', ['id'=>$exist->getId()]);

    }

    /**
     * @Route("/new", name="backend_statut_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($statut);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Success!</strong> Le statut a bien été enregistré");

            return $this->redirectToRoute('backend_statut_index');
        }

        return $this->render('statut/new.html.twig', [
            'statut' => $statut,
            'form' => $form->createView(),
            'show' => "statut"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_statut_show", methods={"GET"})
     */
    public function show(Statut $statut): Response
    {
        return $this->render('statut/show.html.twig', [
            'statut' => $statut,
            'show' => "statut"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_statut_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Statut $statut): Response
    {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "<strong>Success!</strong> Le statut a bien été modifié");


            return $this->redirectToRoute('backend_statut_index');
        }

        return $this->render('statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form->createView(),
            'show' => "statut"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_statut_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Statut $statut): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statut->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($statut);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Success!</strong> Le statut a bien été supprimé");

        }

        return $this->redirectToRoute('backend_statut_index');
    }
}
