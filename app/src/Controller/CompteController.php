<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ResetFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
 * @Route("/compte", name="compte_")
 */

class CompteController extends AbstractController
{
    /**
     * @Route("/parameters", name="parameters")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getuser();

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'messageCompte' => "Connectez-vous pour pouvoir accéder à votre compte utilisateur",
            ]);                
        }else {
            $em = $this->getDoctrine()->getManager();

            //Creation du Formulaire
            $form = $this->createForm(ResetFormType::class, $user);
            $form->handleRequest($request); 

            // Si le formulaire est envoyé et il est valide
            if ($form->isSubmitted() && $form->isValid()){
                $hash = $encoder->encodePassword($user, $form->get('password')->getData());
                // Inialisation du mot de passe
                $user ->setPassword($hash);
                $user = $form->getData();
                $em->persist($user);
                $em->flush();

                return $this->render('compte/compte.html.twig', [
                    'form' => $form->createView(),
                    'success' => 'Votre mot de passe a été modifié avec succès',
                ]);
            };
            return $this->render('compte/compte.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/histories", name="histories")
     */
    public function histories(): Response
    {
        $user = $this->getuser();

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'messageCompte' => "Connectez-vous pour pouvoir accéder à votre compte utilisateur",
            ]);                
        }else {
            //player's histories
            $histories = $user->getHistories();
            return $this->render('compte/compte.html.twig', [
                'histories' => $histories,
            ]);                
        }
    }
}
