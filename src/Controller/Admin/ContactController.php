<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="backend_contact_index", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository): Response
    {
        $exit = $contactRepository->findOneBy([],['id'=>"DESC"]);
        if (!$exit)
            return $this->redirectToRoute('backend_contact_new');
        else
            return $this->redirectToRoute('backend_contact_show',['id'=>$exit->getId()]);

    }

    /**
     * @Route("/new", name="backend_contact_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', "Contact ajouté avec succès!");

            return $this->redirectToRoute('backend_contact_index');
        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
            'show' => 'contact'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_contact_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
            'show' => 'contact'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_contact_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Contact modifié avec succès!");

            return $this->redirectToRoute('backend_contact_index');
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
            'show' => 'contact'
        ]);
    }

    /**
     * @Route("/{id}", name="backend_contact_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();

            $this->addFlash('success', "Contact supprimé avec succès!");
        }

        return $this->redirectToRoute('backend_contact_index');
    }
}
