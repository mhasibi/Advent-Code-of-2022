<?php

class Item {

    public int $id = 0;
    public int $priority = 0;

    public function __construct()
    {
        $this->id = 0;
    }

    public function setPriority(int $priority) {
        $this->priority = $priority;
    }
}

class Rucksack{
    public array $compartments = [];
    public array $itemTypes = [];

    public function __construct()
    {
        $this->itemTypes = array_merge(range('a','z'), range('A','Z'));
    }

    public function findItemPriority (string $item): int {
        return array_search($item, $this->itemTypes) + 1;
    }

    public function createCompartments(string $itemList): array {
        $itemListLength = strlen($itemList);
        if ($itemListLength) {
            $this->compartments[] = substr($itemList, 0, $itemListLength/2);
            $this->compartments[] = substr($itemList, $itemListLength/2, $itemListLength-1);
        }
        return $this->compartments;
    }

    public function findCommonItem() {
        foreach ($text_split=str_split($this->compartments[0]) as $char) {
            if (strpos($this->compartments[1], $char)) {
                return $char;
            }
        }
        return '';
    }
}

$handle = fopen('input.txt', "rb");
$priorities = 0;

if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $rucksack = new Rucksack();
        $rucksack->createCompartments(trim($line));
        $p = $rucksack->findItemPriority($rucksack->findCommonItem());
        $priorities += $p;
    }
    echo  "Priorities = " . $priorities . "<br>";
}