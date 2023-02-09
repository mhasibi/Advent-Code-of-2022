<?php

class ElfPair {
    public array $shift = [0,0];

    public function detectShiftHours(string $shiftString): array {
        $shiftHours = [];
        $shifts = explode(',', $shiftString);
        foreach ($shifts as $shift) {
            $hours = explode('-', $shift);
            $shiftHours[] = $hours;
        }
        return $shiftHours;
    }

    public function fullyOverlaps(Shift $shift_1, Shift $shift_2) {
        if ($shift_1->shift['start'] <= $shift_2->shift['start'] && $shift_1->shift['end'] >= $shift_2->shift['end']) {
            return True;
        }

        if ($shift_1->shift['start'] >= $shift_2->shift['start'] && $shift_1->shift['end'] <= $shift_2->shift['end']) {
            return True;
        }
        return false;
    }

    public function partiallyOverlaps(Shift $shift_1, Shift $shift_2) {
        if (in_array($shift_1->shift['start'], range($shift_2->shift['start'], $shift_2->shift['end']) )||
            in_array($shift_1->shift['end'], range($shift_2->shift['start'], $shift_2->shift['end'])) ){
            return true;
        }

        if (in_array($shift_2->shift['start'], range($shift_1->shift['start'], $shift_1->shift['end']) )||
            in_array($shift_2->shift['end'], range($shift_1->shift['start'], $shift_1->shift['end'])) ){
            return true;
        }

        return false;
    }
}

class Shift {
    public array $shift = [
        'start' => 0,
        'end' => 0
    ];

    public function __construct(array $shift)
    {
            $this->shift['start'] = $shift[0];
            $this->shift['end'] = $shift[1];
    }

}

$handle = fopen('input.txt', "rb");
$overlappedShifts = 0;
$partiallyOverlappedShifts = 0;

if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $elfPair = new ElfPair();
        $shiftHours = $elfPair->detectShiftHours(trim($line));
        $shift_1 = new Shift($shiftHours[0]);
        $shift_2 = new Shift($shiftHours[1]);
        if ($elfPair->fullyOverlaps($shift_1, $shift_2) == True) {
            $overlappedShifts++ ;
        }
        if ($elfPair->partiallyOverlaps($shift_1, $shift_2) == True) {
            $partiallyOverlappedShifts++ ;
        }

    }
}

echo "There are $overlappedShifts shifts that fully overlap with each other." . "<br>";
echo "There are $partiallyOverlappedShifts shifts that partially overlap with each other.";