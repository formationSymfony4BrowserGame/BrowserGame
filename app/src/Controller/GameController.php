<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/game/play", name="play")
     */
    public function play(): Response
    {
        //connected player
        $user = $this->getuser();

        //player's saved games
        $games = $user->getGames();

        return $this->render('game/play.html.twig', [
            'games' => $games,
        ]);                
    }

    /**
     * @Route("/game/new", name="new_game")
     */
    public function newGame(): Response
    {
        return $this->render('game/new.html.twig');
    }

    /**
     * @Route("/game/load", name="load_game")
     */
    public function loadGame(): Response
    {
        return $this->render('game/load.html.twig');
    }

    /**
     * @Route("/game/gamepage", name="game")
     */
    public function game(Request $request): Response
    {
        $get = $request->query->all();
        dump($get['new']);
        return $this->render('game/game.html.twig', [
            ''
        ]);
    }
}
