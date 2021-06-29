<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Game;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/delete")
 * @IsGranted("ROLE_USER")
 */
class DeleteController extends AbstractController
{

    /**
     * @Route("/{id}", name="delete_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }
        $session = new Session();
        $session->invalidate();
        return $this->redirectToRoute('app_logout');
    }
}
