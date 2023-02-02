<?php
$filename = 'input.txt';
$numbers = file($filename);

$max_calories = 0;
$subtotal = 0;

foreach ($numbers as $number) {
    if (trim($number) != '') {
        $subtotal += (int) $number;
    } else {
        if ($subtotal > $max_calories) {
            $max_calories = $subtotal;
        }
        $subtotal = 0;
    }
}

echo $max_calories;
?>