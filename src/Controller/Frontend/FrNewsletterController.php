<?php

namespace App\Controller\Frontend;

use App\Entity\Newsletter;
use App\Form\NewsletterFrontendType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/newsletter")
 */
class FrNewsletterController extends AbstractController
{
    /**
     * @Route("/", name="frontend_newsletter_index")
     */
    public function index()
    {
        return $this->render('frontend/newsletter_message.html.twig');
    }

    /**
     * @Route("/new", name="frontend_newsletter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterFrontendType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $email = $newsletter->getEmail(); //dd($email);

            // Verification d'existence de l'adresse email
            $verifExist = $this->getDoctrine()->getRepository(Newsletter::class)->findOneBy(['email'=>$email]);
            if ($verifExist){
                $this->addFlash('danger', "Vous êtes déjà inscrit(e)");
                return $this->redirectToRoute('frontend_newsletter_index');
            }
            $newsletter->setStatut(true);
            $em->persist($newsletter);
            $em->flush();

            $this->addFlash('success', "Votre email a bien été enregistré");

            return $this->redirectToRoute('frontend_newsletter_index');

        }

        return $this->render('frontend/newsletter_new.html.twig',[
            'newsletter' => $newsletter,
            'form' => $form->createView(),
        ]);
    }
}