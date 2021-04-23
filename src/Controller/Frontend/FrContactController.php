<?php


namespace App\Controller\Frontend;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact")
 */
class FrContactController extends AbstractController
{
    public function index(): Response
    {

    }

    /**
     * @Route("/footer", name="frontend_contact_footer")
     */
    public function footer(): Response
    {

        return $this->render("frontend/contact_footer.html.twig",[
            'contact' =>$this->getDoctrine()->getRepository(Contact::class)->findOneBy([],['id' => 'DESC'])
        ]);
    }
}