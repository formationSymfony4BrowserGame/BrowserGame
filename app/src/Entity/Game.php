<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerCount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentPlayerId;

    /**
     * @ORM\Column(type="json")
     */
    private $hand = [];

    /**
     * @ORM\Column(type="json")
     */
    private $remainingDices = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gameState;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="games")
     */
    private $UserId;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="game")
     */
    private $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerCount(): ?int
    {
        return $this->playerCount;
    }

    public function setPlayerCount(int $playerCount): self
    {
        $this->playerCount = $playerCount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCurrentPlayerId(): ?int
    {
        return $this->currentPlayerId;
    }

    public function setCurrentPlayerId(int $currentPlayerId): self
    {
        $this->currentPlayerId = $currentPlayerId;

        return $this;
    }

    public function getHand(): ?array
    {
        return $this->hand;
    }

    public function setHand(array $hand): self
    {
        $this->hand = $hand;

        return $this;
    }

    public function getRemainingDices(): ?array
    {
        return $this->remainingDices;
    }

    public function setRemainingDices(array $remainingDices): self
    {
        $this->remainingDices = $remainingDices;

        return $this;
    }

    public function getGameState(): ?string
    {
        return $this->gameState;
    }

    public function setGameState(string $gameState): self
    {
        $this->gameState = $gameState;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setGame($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getGame() === $this) {
                $player->setGame(null);
            }
        }

        return $this;
    }
}
