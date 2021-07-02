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
        $data = json_decode($request->getContent(), true);
        
        $game = new Game();

        if (!empty($data)){
            
            $user = $this->getuser();
            $game->setUser($user);
    
            $date = new \DateTime();
            $game->setDate($date);
    
            $game->setPlayerCount($data['playerCount']);
            $game->setCurrentPlayerId($data['currentPlayer']);
            $game->setHand($data['hand']);
            $game->setRemainingDices($data['remainingDices']);
            $game->setGameState($data['state']);
    
            $this->entityManager->persist($game);
            $this->entityManager->flush();
    
            return $this->addFlash('success', 'Votre partie a été sauvegardée avec succès!');    
        }

        return $this->render('accueil/index.html.twig', [
        ]);

    }
}
