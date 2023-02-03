<?php

class Player
{
    public int $score = 0;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}

class Round
{
    public static int $LastID = 0;
    public array $players = [];
    public int $totalOpponentScore = 0;
    public int $totalSelfScore = 0;

    public array $combinations = [
        'BX'=> 1,
        'CX'=> 2,
        'AX'=> 3,
        'AY'=> 4,
        'BY'=> 5,
        'CY'=> 6,
        'CZ'=> 7,
        'AZ'=> 8,
        'BZ'=> 9
    ];

    public static function CreatePlayer(): Player
    {
        $player = new Player(self::$LastID);
        self::$LastID++;
        return $player;
    }

    public function addPlayers(Player $player): self
    {
        $this->players[] = $player;
        return $this;
    }

    public function calculateTotalScore($roundCombination)
    {
        $this->totalSelfScore += $this->combinations[$roundCombination];
    }
}

$round = new Round();

$opponent = $round::CreatePlayer();
$self = $round::CreatePlayer();

$round->addPlayers($opponent);
$round->addPlayers($self);

$handle = fopen('input.txt', "rb");

if ($handle) {
    while (($outcome = fgets($handle)) !== false) {
        $round->calculateTotalScore(trim(str_replace(' ', '',$outcome)));
    }
    echo "Total self score: " . $round->totalSelfScore;
}

