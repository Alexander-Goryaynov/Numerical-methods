<?php
require_once __DIR__ . '/smarty-3/libs/Smarty.class.php';
$x = (float)htmlentities($_POST['x1']);
$y = (float)htmlentities($_POST['x2']);
$eps = (float)htmlentities($_POST['epsilon']);
$alp = (float)htmlentities($_POST['alpha']);
$accuracy = - (int)log10($eps);
$formatStr = '%01.' . (string)$accuracy . 'f';
$tab = [];

function fi1(float $x, float $y): float {
    return sin($x + 0.5) * (-2 * cos($x + 0.5) - 2 * $y + 1.6) + 8 * $x + 6.4 - 4 * sin($y);
}

function fi2(float $x, float $y): float {
    return 2 * cos($y) * sin($y) - 4 * $x * cos($y) + 2 * $y - 1.6 + 2 * cos($x + 0.5) - 3.2 * cos($y);
}

function fmt(float $num, string $formatString): string {
    return (string)sprintf($formatString, $num);
}

$tab[0][0] = $x;
$tab[0][1] = $y;
$tab[0][2] = fi1($x, $y);
$tab[0][3] = fi2($x, $y);
$n = 1;
while(true) {
    $tab[$n][0] = $tab[$n - 1][0] - $alp * $tab[$n - 1][2];
    $tab[$n][1] = $tab[$n - 1][1] - $alp * $tab[$n - 1][3];
    $tab[$n][2] = fi1($tab[$n][0], $tab[$n][1]);
    $tab[$n][3] = fi2($tab[$n][0], $tab[$n][1]);
    $tab[$n][4] = max([abs($tab[$n][0] - $tab[$n - 1][0]), abs($tab[$n][1] - $tab[$n - 1][1])]);
    if ($tab[$n][4] < $eps) {
        break;
    }
    $n++;
}

for ($i = 0; $i < count($tab); $i++) {
    for($j = 0; $j < count($tab[$i]); $j++) {
        $tab[$i][$j] = fmt($tab[$i][$j], $formatStr);
    }
}
$tab[0][4] = "";

$smarty = new Smarty();
$smarty->assign('tab', $tab);
$smarty->display('lab4.tpl');