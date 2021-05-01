## Description

Secret Santa game in PHP (Laravel)

## Installation

```
composer install
```

## Usage

In this example in total we add 4 players.

```php
<?php
$secretSanta = new SecretSanta();
$secretSanta->addPlayer('Player1', 'player1@email.com')
  ->addPlayer('Player2', 'player2@email.com')
  ->addPlayer('Player3', 'player3@email.com')
  ->addPlayer('Player4', 'player4@email.com');
  
foreach ($secretSanta->play() as $player) {
     echo ("{$player->name()} ({$player->email()}): {$player->secretSanta()->name()}\n");
}
```
The above example will output:

```php
Player1 : Player3
Player2 : Player4
Player3 : Player2
Player4 : Player1
```
