<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    private $passwordEncode;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncode = $passwordEncoder;
    }

    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        //dd($userRepository->findBy([],['email'=>"ASC"]));
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findBy([],['email'=>"ASC"]),
            'show' => 'user'
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //Verification d'existence
            $verifExistence = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$user->getEmail()]);
            if ($verifExistence){
                $this->addFlash('danger', "Echec, ce compte utilisateur existe déjà");
                return $this->redirectToRoute('admin_user_new');
            }

            $user->setPassword($this->passwordEncode->encodePassword($user, $user->getPassword()));

            // Affectation des roles
            //dd($user);

            if ($user->getEmail() === 'delrodieamoikon@gmail.com') $user->setRoles(['ROLE_SUPER_ADMIN']); //dd($user);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté");

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'show' => "user"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($this->passwordEncode->encodePassword($user, $user->getPassword())); dd($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'show' => 'user'
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', "Compte utilisateur supprimé avec succès");
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
