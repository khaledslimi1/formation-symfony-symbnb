<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     *
     */
    public function login(AuthenticationUtils $utils)
    {
        $errors = $utils->getLastAuthenticationError();

        $userName = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $errors !== null,
            'userName' => $userName
        ]);
    }

    /**
     * Déconnexion
     *
     * @Route("/logout", name="account_logout")
     *
     */
    public function logout()
    {
        //Rien
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     *
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hach = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hach);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('succes', "Votre compte a été bien créé !");

            return $this->redirectToRoute("account_login");
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'éditer le formulaire de modification de l'utilisateur
     *
     * @Route("/account/edit", name="account_edit")
     *
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Vos modification ont été bien enregistrées !");
        }

        return $this->render("account/profile.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/account/update-password",name="account_password")
     *
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $password = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $password);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($password->getOldPassword(), $user->getHash())) {
                //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError('Le mot de passe tapé n\'est pas votre mot de passe actuel !'));
            } else {
                $newPassword = $password->getNewPassword();
                $hach = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hach);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    "success",
                    "Votre mot de passe a bien été modifié"
                );

                return $this->redirectToRoute('homepage');
            }

        }

        return $this->render("account/password.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account_index")
     *
     * @IsGranted("ROLE_USER")
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
