<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController
{
    private $passwordEncode;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncode = $passwordEncoder;
    }

    /**
     * @Route("/password", name="app_password")
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(PasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $variable = [
                'ancien' => $user->getEmail(),
                'nouveau' => $user->getPassword()
            ];

            // Verification de la validité de l'ancien mot de passe
            $connecte = $this->getUser();
            $paswordValid = $this->passwordEncode->isPasswordValid($connecte, $variable['ancien']);
            if (!$paswordValid){
                $this->addFlash('danger', "Echec! Votre ancien mot de passe n'est pas valide");
                return $this->redirectToRoute('app_password');
            }

            // Enregistrement du nouveau mot de passe
            $connecte->setPassword($this->passwordEncode->encodePassword($connecte, $variable['nouveau']));

            $entityManager->flush();

            $this->addFlash('success', "Votre mot de passe a bien été modifié");

            return $this->render('password/valid.html.twig');
        }

        return $this->render('password/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            //'show' => "password"
        ]);
    }
}
