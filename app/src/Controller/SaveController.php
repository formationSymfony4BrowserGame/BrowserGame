<?php

namespace App\Controller;
use App\Entity\Game;
use App\Entity\Player;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SaveController extends AbstractController
{
    /**
     * @Route("/save", name="save", methods={"POST","GET"})
     */
    public function save(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        //dump($data);
        
        if (!empty($data)){

            $em = $this->getDoctrine()->getManager();
            $game = new Game();
            
            $user = $this->getuser();
            $game->setUser($user);
    
            $date = new \DateTime();
            $game->setDate($date);
    
            $game->setPlayerCount($data['playerCount']);
            $game->setCurrentPlayerId(0);
            $game->setHand($data['hand']);
            $game->setRemainingDices($data['remainingDices']);
            $game->setGameState($data['state']);
    
            $em->persist($game);

            for ($i=0; $i<$data['playerCount']; $i++){

                $player = new Player;
                $player->setPseudo($data['players'][$i]['pseudo']);
                $player->setRanking($data['players'][$i]['ranking']);
                $player->setPickominos($data['players'][$i]['pickominos']);
                $player->setGame($game);
                $em->persist($player);
                $em->flush();

                if ($data['players'][$i]['ranking'] == $data['currentPlayer']) {
                    $id = $player->getId();
                    $game->setCurrentPlayerId($id);
                    $em->persist($game);
                }
            }

            $em->flush();
            $this->addFlash('success', 'Votre partie a été bien sauvegardée!');
        }

        return $this->redirect($this->generateUrl('accueil'));
    }
}
