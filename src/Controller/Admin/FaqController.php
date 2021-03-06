<?php

namespace App\Controller\Admin;

use App\Entity\Faq;
use App\Form\FaqType;
use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/", name="backend_faq_index", methods={"GET"})
     */
    public function index(FaqRepository $faqRepository): Response
    {
        return $this->render('faq/index.html.twig', [
            'faqs' => $faqRepository->findAll(),
            'show' => "faq"
        ]);
    }

    /**
     * @Route("/new", name="backend_faq_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $page = $request->get('ajouter');

            // Verification d'existence
            $verif = $this->getDoctrine()->getRepository(Faq::class)->findOneBy(['question'=>$faq->getQuestion()]);
            if ($verif){
                $this->addFlash('danger', "<strong>Echec!</strong> Cet element existe déjà. ");

                return $this->redirectToRoute('backend_faq_new');
            }

            $entityManager->persist($faq);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'élément a bien été enregistré!");
            if ($page){
                return $this->redirectToRoute('backend_faq_new');
            }

            return $this->redirectToRoute('backend_faq_index');
        }

        return $this->render('faq/new.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'show' => "faq"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_faq_show", methods={"GET"})
     */
    public function show(Faq $faq): Response
    {
        return $this->render('faq/show.html.twig', [
            'faq' => $faq,
            'show' => "faq"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_faq_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faq $faq): Response
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $ajouter = $request->get('ajouter');

            $this->addFlash('success', "<strong>Succes!</strong> L'élément a bien été modifié!");

            if ($ajouter) return  $this->redirectToRoute('backend_faq_new');

            return $this->redirectToRoute('backend_faq_index');
        }

        return $this->render('faq/edit.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'show' => "faq"
        ]);
    }

    /**
     * @Route("/{id}", name="backend_faq_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Faq $faq): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faq->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($faq);
            $entityManager->flush();

            $this->addFlash('success', "<strong>Succes!</strong> L'élément a bien été supprimé.");
        }

        return $this->redirectToRoute('backend_faq_index');
    }
}
