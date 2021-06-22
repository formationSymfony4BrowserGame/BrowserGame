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

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir lancer le jeu",
            ]);                
        }else {
            //player's saved games
            $games = $user->getGames();
            return $this->render('game/play.html.twig', [
                'games' => $games,
            ]);                
        }
    }

    /**
     * @Route("/game/new", name="new_game")
     */
    public function newGame(): Response
    {
        // connected user
        $user = $this->getuser();

        return $this->render('game/new.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/game/load", name="load_game")
     */
    public function loadGame(): Response
    {
        //connected player
        $user = $this->getuser();

        //player's saved games
        $games = $user->getGames();

        return $this->render('game/load.html.twig', [
            'games' => $games,
        ]);                
    }

    /**
     * @Route("/game/load/{id}", name="players")
     */
    public function playersloadGame($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $game= $em->getRepository(Game::class)->findOneBy([
            'id' => $id,
        ]);
        $players = $game-> getPlayers();
        return $this->render('game/load.html.twig', [
            'players' => $players,
        ]);
    }

    /**
     * @Route("/game/", name="game")
     */
    public function game(Request $request): Response
    {
        return $this->render('game/game.html.twig', [
            'players' => $request->request->all()
        ]);
    }

    /**
     * @Route("/game/{id}", name="loaded")
     */
    public function loadedGame(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $game= $em->getRepository(Game::class)->findOneBy([
            'id' => $id,
        ]);
        $playerNames = $game->getPlayers()->map(function($player) {
            return $player->getPseudo();
        });
        return $this->render('game/game.html.twig', [
            'players' => $game-> getPlayers(),
            'init' => [
                'playerCount' => $game->getPlayerCount(),
                'currentPlayerId' => $game->getCurrentPlayerId(),
                'hand' => $game->getHand(),
                'remainingDices' => $game->getRemainingDices(),
                'gameState' => $game->getGameState(),
                'players' => $game->getPlayers(),
            ]
        ]);
    }
}
