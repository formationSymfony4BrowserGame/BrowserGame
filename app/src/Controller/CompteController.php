<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ResetFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/compte", name="compte_")
 * @IsGranted("ROLE_USER")
 */

class CompteController extends AbstractController
{
    /**
     * @Route("/parameters", name="parameters", methods={"GET","POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getuser();
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
                'user'   => $user,
            ]);
        };
        return $this->render('compte/compte.html.twig', [
            'form' => $form->createView(),
            'user'   => $user,
        ]);
    }

    /**
     * @Route("/histories", name="histories")
     */
    public function histories(): Response
    {
        $user = $this->getuser();

        //player's histories
        $histories = $user->getHistories();
        return $this->render('compte/compte.html.twig', [
            'histories' => $histories,
        ]);                
    }
}
