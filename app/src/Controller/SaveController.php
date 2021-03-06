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
     * @Route("/save", name="save", methods={"POST"})
     */
    public function save(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        
        $savedGame = null;
        if (!empty($data)) {
            $user = $this->getuser();
            $em = $this->getDoctrine()->getManager();
            $savedGame = ($data['idGame'] == null) ? null : $em->getRepository(Game::class)->find($data['idGame']);
            if($savedGame != null && $savedGame->getUser() == $user){
                // Partie est déjà sauvegardée, on l'actualise avec les nouvelles données

                $date = new \DateTime();
                $savedGame->setDate($date);

                $savedGame->setCurrentPlayerId(0);
                $savedGame->setHand($data['hand']);
                $savedGame->setRemainingDices($data['remainingDices']);
                $savedGame->setGameState($data['state']);
        
                $em->persist($savedGame);
    
                for ($i=0; $i<$data['playerCount']; $i++){
    
                    $player = $em->getRepository(Player::class)->find($data['players'][$i]['id']);
                    if ($player == null){
                        $player = new Player();
                    }
                    $player->setPseudo($data['players'][$i]['pseudo']);
                    $player->setRanking($data['players'][$i]['ranking']);
                    $player->setPickominos($data['players'][$i]['pickominos']);
                    $player->setGame($savedGame);
                    $em->persist($player);
                    $em->flush();
    
                    if ($data['players'][$i]['ranking'] == $data['currentPlayer']) {
                        $id = $player->getId();
                        $savedGame->setCurrentPlayerId($id);
                        $em->persist($savedGame);
                    }
                }
    
                $em->flush();
                $this->addFlash('success', 'Votre partie a été bien sauvegardée!');    

            } else if($savedGame == null) {
                //Sauvegarder une nouvelle partie
                $game = new Game();
                
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
    
                    $player = new Player();
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
        }
        // return $this->redirect($this->generateUrl('accueil'));
        $res = new Response();
        $res->setContent(json_encode(($savedGame == null) ? null : $savedGame->getId()));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * @Route("/save", name="saveRedirect", methods={"GET"})
     */
    public function saveRedirect(Request $request): Response
    {
        return $this->redirect($this->generateUrl('accueil'));
    }
}