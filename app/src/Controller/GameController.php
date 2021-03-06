<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
                'message' => "Connectez-vous pour pouvoir lancer le jeu",
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

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir lancer le jeu",
            ]);                
        }

        //player's saved games
        $games = $user->getGames();

        return $this->render('game/load.html.twig', [
            'games' => $games,
        ]);                
    }

    /**
     * @Route("/game/load/{id}/players", name="load_players")
     */
    public function loadPlayers(int $id): Response {
        $user = $this->getuser();

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir lancer le jeu",
            ]);                
        }

        $em = $this->getDoctrine()->getManager();
        $game= $em->getRepository(Game::class)->findOneBy([
            'id' => $id,
        ]);

        // get the player names for the game page
        $playerNames = $game->getPlayers()->map(function($player) {
            return $player->getPseudo();
        });

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $response = new Response();
        $response->setContent($serializer->serialize($playerNames, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/game/", name="game")
     */
    public function game(Request $request): Response
    {
        $user = $this->getuser();

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir lancer le jeu",
            ]);                
        }

        return $this->render('game/game.html.twig', [
            'players' => $request->request->all()
        ]);
    }

    /**
     * @Route("/game/{id}", name="loaded")
     */
    public function loadedGame($id): Response
    {
        $user = $this->getuser();

        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir lancer le jeu",
            ]);                
        }
        
        $em = $this->getDoctrine()->getManager();
        $game= $em->getRepository(Game::class)->findOneBy([
            'id' => $id,
        ]);

        // get the player names for the game page
        $playerNames = $game->getPlayers()->map(function($player) {
            return $player->getPseudo();
        });

        $user = $this->getuser();

        //check if user is connected and if he's the game proprietary
        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir lancer le jeu",
            ]);                
        } else if ($user !== $game->getUser()) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Cette partie ne vous appartient pas",
            ]);    
        }

        $serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);

        $jsonGame = $serializer->serialize($game, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['user', 'date'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            }
        ]);

        return $this->render('game/game.html.twig', [
            'players' => $playerNames,
            'game' => $jsonGame,
        ]);
    }
}
