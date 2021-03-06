<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Game;
use App\Entity\History;
use App\Entity\Player;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 6; ++$i) {
                        
            //Création des users
            $user = new User();
            $currentUser = 'user'.$i;
            $user->setPseudo($currentUser);
            $currentMail = $currentUser.'@user.fr';
            $user->setMail($currentMail);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $currentUser));
            $manager->persist($user);


            //Création d'une historique de jeu pour le 4 éme user
            if($i === 4){
                $history = new History();
                $history->setPlayers([$currentUser, 'player1', 'player2', 'player3']);
                $historydate = new DateTime();
                $historydate->modify( '-1 day' );
                $history->setDate($historydate);
                $history->setUser($user);
                $manager->persist($history);
            }

            //Création des jeux sauvgardés pour tous les users
            $game = new Game();
            $game->setUser($user);
            $game->setPlayerCount(4);
            $now = new DateTime();
            $game->setDate($now);
            $game->setCurrentPlayerId(0);
            $game->setHand([6,6,6,6]);
            $game->setRemainingDices([1,2,3,4]);
            $game->setGameState('afterThrowState');
            $manager->persist($game);

            //Création des 4 joueurs qui participent au même jeu sauvgardé
            for ($j = 1; $j < 5; ++$j){
                $player = new Player();
                // Le premier joueur qui est l'utilisateur connecté
                if($j === 1){
                    $player->setPseudo($currentUser);
                    $player->setRanking(0);
                    $player->setPickominos([32,33]);
                    $player->setGame($game);
                    
                // Les 3 autres joueurs
                }else{
                    $player->setPseudo('pseudo' .$i.$j);
                    $player->setRanking($j-1);
                    $player->setPickominos([(int)(2 .$j)]);
                    $player->setGame($game);
                } 

                $manager->persist($player);
                $manager->flush();
                $id = $player->getId();
            }

            $game->setCurrentPlayerId($id);
            $manager->persist($game);

        }

        $manager->flush();
    }
    
}
