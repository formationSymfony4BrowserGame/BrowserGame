<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScoreGameController extends AbstractController
{
    /**
     * @Route("/game/score", name="score_game")
     */
    public function scoreGame(): Response
    {
        return $this->render('game/scoreGame.html.twig');                
    }

    /**
     * @Route("/game/score/{id}", name="delete_game", methods={"POST"})
     */
    public function deleteGame(Game $games, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$games->getId(), $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->remove($games);
            $em->flush();
        }
        return $this->redirectToRoute('play', [
            'games' => $games,
        ]);
    }
}
