<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/delete")
 * @IsGranted("ROLE_USER")
 */
class DeleteSavedGameController extends AbstractController
{
    /**
     * @Route("/game/{id}", name="delete_saved_game", methods="DELETE")
     */
    public function deleteSavedGame(Game $game, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->remove($game);
            $em->flush();
        }
        return $this->redirectToRoute('game');
    }
}
