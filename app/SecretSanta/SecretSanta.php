<?php

namespace  App\SecretSanta;

use  App\SecretSanta\Exceptions\PlayerException;
use  App\SecretSanta\Exceptions\SecretSantaException;

/**
 * Class SecretSanta
 * @package SecretSanta
 */
class SecretSanta
{
    /** @var PlayersCollection */
    private $players;
    /** @var  array */
    private $combination;

    /**
     * SecretSanta constructor.
     */
    public function __construct()
    {
        $this->players = new PlayersCollection();
    }

    /**
     * Add single player to the game
     * @param string $name
     * @param string $email
     * @return SecretSanta
     */
    public function addPlayer($name, $email)
    {
        $this->players->addPlayer(Player::create($name, $email));

        return $this;
    }

    /**
     * @return Player[]
     * @throws SecretSantaException
     */
    public function play()
    {
        try {
            $this->combinePlayers();

            return $this->associatePlayers();
        } catch (SecretSantaException $exception) {
            throw  $exception;
        } catch (\Exception $exception) {
            throw new SecretSantaException('Error during play, impossible to find secret santa, try again', $exception);
        }
    }

    /**
     * @throws SecretSantaException
     */
    private function combinePlayers()
    {
        if (count($this->players) < 3) {
            throw new SecretSantaException("Not enough players to play, at least 3 players are required");
        }

        $retry = count($this->players) + $this->players->countExclusivePlayers();

        while (!$this->tryMatchSecretSantaPlayers() && $retry > 0 ) {
            $retry--;
        }

        if (!$this->isValidCombination()) {
            throw new SecretSantaException("Not enough players to play");
        }
    }

    /**
     * @return bool
     */
    private function tryMatchSecretSantaPlayers()
    {
        $this->combination = [];
        $secretPlayers = $this->players->shufflePlayers();
        foreach ($this->players->players() as $playerId => $player) {
            foreach ($secretPlayers as $secretPlayer) {
                if ($this->isValidSecretSanta($player, $secretPlayer)) {
                    $this->combination[$player->id()] = $secretPlayer->id();
                    unset ($secretPlayers[$secretPlayer->id()]);
                    break;
                }
            }
        }

        return $this->isValidCombination();
    }

    /**
     * @param Player $player
     * @param Player $secretPlayer
     * @return bool
     */
    private function isValidSecretSanta(Player $player, Player $secretPlayer)
    {
        if ($player->id() != $secretPlayer->id() && !$this->players->areExclusive($player, $secretPlayer)) {
            if (!in_array($secretPlayer->id(), $this->combination)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function isValidCombination()
    {
        return count($this->combination) > 0 && count($this->combination) == count($this->players);
    }

    private function associatePlayers()
    {
        foreach ($this->combination as $playerId => $secretSantaId) {
            $this->players->player($playerId)->setSecretSanta(
                $this->players->player($secretSantaId)
            );
        }

        return $this->players->players();
    }
}
