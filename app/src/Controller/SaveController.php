<?php

namespace App\Controller;
use App\Entity\Game;
use App\Entity\Player;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SaveController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/save", name="save", methods={"POST"})
     */
    public function save(Request $request): Response
    {
        /*$data = json_decode($request->getContent());
        $gameData = $this->json($data);*/

        $game = new Game();
        $checkData = $request->request->all();

        if (!empty($checkData)){
            
            $playerCount = $request->request->get('playerCount');

            $currentPlayerId = $request->request->get('currentPlayer');
    
            $hand = $request->request->get('hand');
            $handArray = explode(',', $hand);
    
            $remainingDices = $request->request->get('remainingDices');
            $remainingDicesArray = explode(',', $remainingDices);
    
            $gameState = $request->request->get('gameState');
    
            $user = $this->getuser();
            $game->setUser($user);
    
            $date = new \DateTime();
            $game->setDate($date);
    
            $game->setPlayerCount($playerCount);
            $game->setCurrentPlayerId($currentPlayerId);
            $game->setHand($handArray);
            $game->setRemainingDices($remainingDicesArray);
            $game->setGameState($gameState);
    
            $this->entityManager->persist($game);
            $this->entityManager->flush();
    
    
            for ($j = 0; $j < (int)$playerCount; ++$j){
    
                $player = new Player();
    
                $playerData = $request->request->get('player'.$j);
                $playerDataArray = explode(',', $playerData);
    
                $player->setPseudo($playerDataArray[0]);
    
                $picko = [];
    
                for ($i=1; $i< Count($playerDataArray); ++$i){
                    array_push($picko,$playerDataArray[$i]);
                }
    
                $player->setPickominos($picko);
    
                $player->setRanking($j);
    
                $player->setGame($game);
    
                $this->entityManager->persist($player);
                $this->entityManager->flush();
            }

            return $this->addFlash('success', 'Votre partie a été sauvegardée avec succès!');    
        }

        return $this->render('accueil/index.html.twig', [
        ]);

    }
}
