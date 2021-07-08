<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Entity\History;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScoreGameController extends AbstractController
{
    /**
     * @Route("/score/{id}", name="score_game")
     */
    public function scoreGame($id): Response
    {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);

        if (!$game) {
            throw $this->createNotFoundException(
                'Partie non trouvé '.$id
            );
        }

        $players = $game->getPlayers();
        $sortedPlayers = [];
        foreach($players as $player){
            $pickominos = $player->getPickominos();
            $score = 0;
            foreach($pickominos as $pickomino){
                $score += floor(($pickomino - 21) / 4) + 1; 
            }
            $sortedPlayers[] = [
                'pseudo' => $player->getPseudo(),
                'score' => $score
            ];
        }
        usort($sortedPlayers,function($a, $b){
            if ($a["score"]>$b["score"]){
                return -1;
            }
            else if ($a["score"]==$b["score"]){
                return 0;
            }
            else if ($a["score"]<$b["score"]){
                return 1;
            }
        });

        return $this->render('score/scoreGame.html.twig',[
            'game'=>$game,
            'players'=>$sortedPlayers
        ]);

    }
    

    /**
     * @Route("/score/delete/{id}", name="delete_game", methods={"POST"})
     */
    public function deleteGame($id , Request $request): Response
    {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);

        $user = $this->getuser();

        //check if user is connected and if he's the game proprietary
        if (empty($user)) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Connectez-Vous pour pouvoir accéder à cette page",
            ]);                
        } else if ($user !== $game->getUser()) {
            return $this->render('accueil/index.html.twig', [
                'message' => "Cette partie ne vous appartient pas",
            ]);    
        }

        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->get('_token'))){
            
            $em = $this->getDoctrine()->getManager();

            // delete the game from database
            $em->remove($game);

            // prepare the player array for the history table
            $players = $game->getPlayers();
            $historyPlayers = [];
            foreach($players as $player){
                $pickominos = $player->getPickominos();
                $score = 0;
                foreach($pickominos as $pickomino){
                    $score += floor(($pickomino - 21) / 4) + 1; 
                }
                $historyPlayers[] = [
                    'pseudo' => $player->getPseudo(),
                    'score' => $score
                ];
            }
            usort($historyPlayers,function($a, $b){
                if ($a["score"]>$b["score"]){
                    return -1;
                }
                else if ($a["score"]==$b["score"]){
                    return 0;
                }
                else if ($a["score"]<$b["score"]){
                    return 1;
                }
            });


            // create the new history entry in the database
            $newHistory = new History();
            $newHistory->setPlayers($historyPlayers);
            $newHistory->setDate(new \DateTime());
            $newHistory->setUser($user);
            $em->persist($newHistory);

            $em->flush();
        }
        return $this->redirectToRoute('accueil');
    }
}
