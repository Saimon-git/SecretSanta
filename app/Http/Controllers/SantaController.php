<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\SecretSanta\SecretSanta;

class SantaController extends Controller
{
    public function index()
    {
        $secretSanta = new SecretSanta();
        $secretSanta->addPlayer('Player1', 'player1@email.com')
            ->addPlayer('Player2', 'player2@email.com')
            ->addPlayer('Player3', 'player3@email.com' )
            ->addPlayer('Player4', 'player4@email.com');

        foreach ($secretSanta->play() as $player) {
            echo ("{$player->name()} : {$player->secretSanta()->name()}\n");
        }
    }
}
