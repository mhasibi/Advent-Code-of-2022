<?php

class Player
{
    public int $score = 0;

    public function __construct(int $id)
    {
        echo "Player is being created with ID " . $id . "<br>";
        $this->id = $id;
    }

    public function getId(): int
    {
        echo "Get Player ID " . "<br>" ;
        return $this->id;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function playRock(): self
    {
        echo "Play Rock" . "<br>";
        $this->score += 1;
        return $this;
    }

    public function playPaper(): self
    {
        echo "Play Paper" . "<br>";
        $this->score += 2;
        return $this;
    }

    public function playScissors(): self
    {
        echo "Play Scissors" . "<br>";
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

    public function roundLost(): self
    {
        echo "Round Lost " . "<br>";
        $this->players[0]->score += 3;
        $this->calculateTotalScores();
        $this->resetIndividualScores();
        return $this;
    }

    public function roundDraw(): self
    {
        echo "Round Draw " . "<br>";
        foreach ($this->players as $player) {
            $player->score += 3;
        }
        $this->calculateTotalScores();
        $this->resetIndividualScores();
        return $this;
    }

    public function roundWon(): self
    {
        echo "Round Won " . "<br>";
        $this->players[1]->score += 6;
        $this->calculateTotalScores();
        $this->resetIndividualScores();
        return $this;
    }

    public function resetIndividualScores(): void {
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
        echo "Outcome: " . $outcome . "<br>";
        $opponentMove = $outcome[0];
        $selfMove = $outcome[2];

        switch ($opponentMove) {
            case 'A':
                return $opponent->playRock();
            case 'B':
                return $opponent->playPaper();
            case 'C':
                return $opponent->playScissors();
        }

        switch ($selfMove) {
            case 'X':
                return $self->playRock();
            case 'Y':
                return $self->playPaper();
            case 'Z':
                return $self->playScissors();
        }

        echo $opponent->score . " vs. " . $self->score . "<br>";

        if ($opponent->getScore() > $self->getScore()) {
            $round->roundLost();
        }

        if ($opponent->getScore() == $self->getScore()) {
            $round->roundDraw();
        }

        if ($opponent->getScore() < $self->getScore()) {
            $round->roundWon();
        }
    }
    echo "total self score: " . $round->totalSelfScore;
}

