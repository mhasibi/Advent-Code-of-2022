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

    public function getScore(): int
    {
        return $this->score;
    }

    public function playRock(): self
    {
        $this->score += 1;
        return $this;
    }

    public function playPaper(): self
    {
        $this->score += 2;
        return $this;
    }

    public function playScissors(): self
    {
        $this->score += 3;
        return $this;
    }

    public function resetScore(): self
    {
        $this->score = 0;
        return $this;
    }
}

class Round
{
    public static int $LastID = 0;
    public array $players = [];
    public int $totalOpponentScore = 0;
    public int $totalSelfScore = 0;

    public array $combinations = [
        'winning' => ['AY', 'BZ', 'CX'],
        'draw' => ['AX', 'BY', 'CZ'],
        'losing' => ['AZ', 'BX', 'CY']
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

    public function decideRound($roundCombination)
    {
        if (in_array($roundCombination, $this->combinations['winning'])) {
            $this->roundWon();
        }
        if (in_array($roundCombination, $this->combinations['draw'])) {
            $this->roundDraw();
        }
        if (in_array($roundCombination, $this->combinations['losing'])) {
            $this->roundLost();
        }
    }

    public function roundLost(): self
    {
        $this->players[0]->score += 6;
        $this->calculateTotalScores();
        $this->resetIndividualScores();
        return $this;
    }

    public function roundDraw(): self
    {
        foreach ($this->players as $player) {
            $player->score += 3;
        }
        $this->calculateTotalScores();
        $this->resetIndividualScores();
        return $this;
    }

    public function roundWon(): self
    {
        $this->players[1]->score += 6;
        $this->calculateTotalScores();
        $this->resetIndividualScores();
        return $this;
    }

    public function resetIndividualScores(): void
    {
        foreach ($this->players as $player) {
            $player->resetScore();
        }
    }

    public function calculateTotalScores(): void
    {
        $this->totalOpponentScore += $this->players[0]->score;
        $this->totalSelfScore += $this->players[1]->score;
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
        $opponentMove = $outcome[0];
        $selfMove = $outcome[2];

        switch (trim($opponentMove)) {
            case 'A':
                $opponent->playRock();
                break;
            case 'B':
                $opponent->playPaper();
                break;
            case 'C':
                $opponent->playScissors();
                break;
        }

        switch ($selfMove) {
            case 'X':
                $self->playRock();
                break;
            case 'Y':
                $self->playPaper();
                break;
            case 'Z':
                $self->playScissors();
                break;
        }

        $round->decideRound($opponentMove . $selfMove);
    }
    echo "Total self score: " . $round->totalSelfScore;
}

