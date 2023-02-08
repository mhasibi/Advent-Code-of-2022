<?php

class Item {
    public array $itemTypes = [];
    public array $itemObject = [
        'name'=> '',
        'priority' => ''
    ];

    public function __construct(string $char)
    {
        $this->itemObject['name'] = $char;
        $this->itemTypes = array_merge(range('a','z'), range('A','Z'));
        $this->findItemPriority($char);
    }

    public function findItemPriority (string $char): self {
        $this->itemObject['priority'] = array_search($char, $this->itemTypes) + 1;
        return $this;
    }

    public function getItemPriority() {
        return $this->itemObject['priority'];
    }
}


class Rucksack{
    public array $compartments = [];

    public function createDoubleCompartments(string $itemList): self {
        $itemListLength = strlen($itemList);
        if ($itemListLength) {
            $this->compartments[] = str_split(substr($itemList, 0, $itemListLength/2));
            $this->compartments[] = str_split(substr($itemList, $itemListLength/2, $itemListLength-1));
        }
        return $this;
    }

    public function findCommonItem(array $compartments) {
        foreach ($compartments[0] as $char) {
            foreach (array_slice($compartments, 1, count($compartments)-1) as $compartment) {
                if (in_array($char, $compartment)) {
                    return $char;
                }
            }
        }
        return '';
    }

    public function findGroupBadge(array $itemList) {
        $charOut = '';
        for ($i = 0 ; $i < count($itemList[0]) ; $i++) {
            $char = $itemList[0][$i];
            if (in_array($char, $itemList[1]) && in_array($char, $itemList[2])) {
                    $charOut= $char;
            }
        }
        return $charOut;
    }


}

$handle = fopen('input.txt', "rb");
$priorities = 0;
$groupPriorities = 0;

if ($handle) {
    $lines = [];
    $groupItems = [];
    while (($line = fgets($handle)) !== false) {
        $rucksack = new Rucksack();
        $rucksack->createDoubleCompartments(trim($line));
        $commonItem = new Item($rucksack->findCommonItem($rucksack->compartments));
        $p = $commonItem->getItemPriority();
        $priorities += $p;

        $groupItems[] = str_split(trim($line));
        if (count($groupItems) == 3) {
            $groupBadgeItem = $rucksack->findGroupBadge($groupItems);
            $groupBadge = new Item($groupBadgeItem);
            $groupPriorities += $groupBadge->getItemPriority();
            $groupItems = [];
        }
    }

}

echo  "Priorities = " . $priorities . "<br>";
echo  "Group Priorities = " . $groupPriorities . "<br>";