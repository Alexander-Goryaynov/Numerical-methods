<?php

require_once __DIR__ . '/jpgraph-4/src/jpgraph.php';
require_once __DIR__ . '/jpgraph-4/src/jpgraph_line.php';
require_once __DIR__ . '/smarty-3/libs/Smarty.class.php';

$x0 = 0.0;
if (isset($_POST['x0'])) {
    $x0 = (float) htmlentities($_POST['x0']);
}

// Точность после запятой.
define('FMT', '%01.3f');

$x = [0.357, 0.871, 1.567, 2.032, 2.628];
$y = [0.548, 1.012, 1.159, 0.694, -0.503];
// осторожно, АЛИНА СНИЗУ
//$x = [0.134, 0.561, 1.341, 2.291, 6.913];
//$y = [2.156, 3.348, 3.611, 4.112, 4.171];
/* АЛИНА */

function getLagrangeCoefficients(array $x, array $y): array
{
    $p = [];
    $p[0][0] = ($x[0] - $x[1]) * ($x[0] - $x[2]) * ($x[0] - $x[3]) * ($x[0] - $x[4]);
    $p[1][0] = ($x[1] - $x[0]) * ($x[1] - $x[2]) * ($x[1] - $x[3]) * ($x[1] - $x[4]);
    $p[2][0] = ($x[2] - $x[0]) * ($x[2] - $x[1]) * ($x[2] - $x[3]) * ($x[2] - $x[4]);
    $p[3][0] = ($x[3] - $x[0]) * ($x[3] - $x[1]) * ($x[3] - $x[2]) * ($x[3] - $x[4]);
    $p[4][0] = ($x[4] - $x[0]) * ($x[4] - $x[1]) * ($x[4] - $x[2]) * ($x[4] - $x[3]);
    for ($i = 0; $i < 5; $i++) {
        $p[$i][1] = 1 / $p[$i][0];
        $p[$i][2] = $p[$i][1] * $y[$i];
    }
    return $p;
}

function lagrangeFunc(float $xCur, array $x, array $p)
{
    return ($p[0][2] * ($xCur - $x[1]) * ($xCur - $x[2]) * ($xCur - $x[3]) * ($xCur - $x[4]) +
        $p[1][2] * ($xCur - $x[0]) * ($xCur - $x[2]) * ($xCur - $x[3]) * ($xCur - $x[4]) +
        $p[2][2] * ($xCur - $x[0]) * ($xCur - $x[1]) * ($xCur - $x[3]) * ($xCur - $x[4]) +
        $p[3][2] * ($xCur - $x[0]) * ($xCur - $x[1]) * ($xCur - $x[2]) * ($xCur - $x[4]) +
        $p[4][2] * ($xCur - $x[0]) * ($xCur - $x[1]) * ($xCur - $x[2]) * ($xCur - $x[3]));
}

function getLagrangePoints(float $xStart, float $h, array $x, array $p): array
{
    $l = [];
    $l[0][0] = $xStart;
    // Правая граница графика по X
    $xEnd = $x[count($x) - 1];
    $i = 0;
    while (true) {
        if ($i !== 0) {
            $l[$i][0] = $l[$i - 1][0] + $h;
        }
        $l[$i][1] = lagrangeFunc($l[$i][0], $x, $p);                
        if ($l[$i][0] > $xEnd) {
            return $l;
        }
        $i++;
    }    
}

function getLagrangeFormula(array $p, array $x): string
{
    $res = "";
    for ($i = 0; $i < count($p); $i++) {
        if ($p[$i][2] >= 0) {
            $res .= sprintf(FMT, $p[$i][2]);
        } else {
            $res .= '(' . sprintf(FMT, $p[$i][2]) . ')';
        }
        for ($j = 0; $j < count($x); $j++) {
            if ($j === $i) {
                continue;
            }
            $res .= ' &#8226; (X - ' . sprintf(FMT, $x[$j]) . ')';
        }
        if ($i !== count($p) - 1) {
            $res .= '+';
        }
    }
    return $res;
}

function newtonFunc(float $x, array $d): float {
    return ($d[0][1] +
        $d[0][2] * ($x - $d[0][0]) +
        $d[0][3] * ($x - $d[0][0]) * ($x - $d[1][0]) +
        $d[0][4] * ($x - $d[0][0]) * ($x - $d[1][0]) * ($x - $d[2][0]) +
        $d[0][5] * ($x - $d[0][0]) * ($x - $d[1][0]) * ($x - $d[2][0]) * ($x - $d[3][0]));
}

function getNewtonPoints(float $xStart, float $h, array $x, array $d): array
{
    $n = [];
    $n[0][0] = $xStart;
    $xEnd = $x[count($x) - 1];
    $i = 0;
    while(true) {
        if ($i !== 0) {
            $n[$i][0] = $n[$i - 1][0] + $h;
        }
        $n[$i][1] = newtonFunc($n[$i][0], $d);
        if ($n[$i][0] > $xEnd) {
            return $n;
        }
        $i++;
    }
}

function getFinalDiffs(array $y): array
{
    $m = [];
    for ($i = 0; $i < count($y[0]); $i++) {
        $m[$i][0] = $y[$i];
    }
    for ($j = 1; $j < count($m); $j++) {
        for ($i = 0; $i < count($m); $i++) {
            if ($i + $j < 5) {
                $m[$i][$j] = $m[$i + 1][$j - 1] - $m[$i][$j - 1];
            }
        }
    }
    return $m;
}

function getDividedDiffs(array $x, array $y): array
{
    $m = [];
    for ($i = 0; $i < count($x); $i++) {
        $m[$i][0] = $x[$i];
        $m[$i][1] = $y[$i];
    }
    $n = 0;
    for ($j = 2; $j < count($x) + 1; $j++) {
        for ($i = 0; $i < count($x); $i++) {
            if ($i + $j < count($x) + 1) {
                $m[$i][$j] = $m[$i + 1][$j - 1] - $m[$i][$j - 1];
                $m[$i][$j] /= $m[$i + $n + 1][0] - $m[$i][0];
            }
        }
        $n++;
    }
    return $m;
}

function buildGraph(array $datasets): void
{
    // Задаём размер изображения
    $graph = new Graph(1200, 700);
    // Дробные значения на осях
    $graph->SetScale('linlin');
    // Название графика
    $graph->title->Set('Полиномы');
    // Названия осей.
    $graph->xaxis->title->Set('x');
    $graph->yaxis->title->Set('y');
    
    foreach ($datasets as $dataset) {
        $plot = new LinePlot($dataset[1], $dataset[0]);        
        $graph->Add($plot);
    }

    // Сохраняем в файл
    $graph->img->SetImgFormat('png');
    $fileName = "graphs/polynom.png";
    $graph->Stroke($fileName);
}

function transposeMatrix(array &$m): void
{
    $t = [];
    for ($i = 0; $i < count($m); $i++) {
        for ($j = 0; $j < count($m[$i]); $j++) {
            $t[$j][$i] = $m[$i][$j];
        }
    }
    $m = $t;
}

// Полином Лагранжа
$coefs = getLagrangeCoefficients($x, $y);
$lagrFormula = getLagrangeFormula($coefs, $x);
$lagrPoints = getLagrangePoints(0, 0.1, $x, $coefs);
$lagrResult = sprintf(FMT, lagrangeFunc($x0, $x, $coefs));
transposeMatrix($lagrPoints);

// Полином Ньютона
$dividedDiffs = getDividedDiffs($x, $y);
$newtonPoints = getNewtonPoints(0, 0.1, $x, $dividedDiffs);
$newtonResult = sprintf(FMT, newtonFunc($x0, $dividedDiffs));
transposeMatrix($newtonPoints);

// Построение графиков
$datasets = [$lagrPoints, $newtonPoints];
buildGraph($datasets);

$smarty = new Smarty();
$smarty->assign('lagrFormula', $lagrFormula);
$smarty->assign('lagrResult', $lagrResult);
$smarty->assign('newtonResult', $newtonResult);
$smarty->assign('x0', $x0);
$smarty->display('lab5.tpl');
