<?php
$filename = 'day-1.txt';
$numbers = file($filename);

$max_calories = [];
$subtotal = 0;

foreach ($numbers as $number) {
    if (trim($number) != '') {
        $subtotal += (int) $number;
    } else {
        $max_calories[] = $subtotal;
        $subtotal = 0;
    }
}
rsort($max_calories);
$top_three_sum = 0;
for ($i=0 ; $i < 3; $i++) {
    $top_three_sum += $max_calories[$i];
}
print_r(array_slice($max_calories, 0 , 3));
echo "<br>" . $top_three_sum;

?>