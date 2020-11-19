<?php

require_once __DIR__ . '/jpgraph-4/src/jpgraph.php';
require_once __DIR__ . '/jpgraph-4/src/jpgraph_line.php';
require_once __DIR__ . '/smarty-3/libs/Smarty.class.php';
require_once __DIR__ . '/lab5LagrangeLogic.php';
require_once __DIR__ . '/lab5NewtonLogic.php';
require_once __DIR__ . '/lab5SplineLogic.php';

$x0 = 0.0;
if (isset($_POST['x0'])) {
    $x0 = (float) htmlentities($_POST['x0']);
}

// Точность после запятой
define('FMT', '%01.3f');
// Шаг графика
define('STEP', 0.1);

$x = [0.357, 0.871, 1.567, 2.032, 2.628];
$y = [0.548, 1.012, 1.159, 0.694, -0.503];

function buildGraph(array $datasets, array $lineColors): void
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
    
    for($i = 0; $i < count($datasets); $i++) {
        $dataset = $datasets[$i];
        $color = $lineColors[$i];
        $plot = new LinePlot($dataset[1], $dataset[0]);        
        $graph->Add($plot);
        $plot->SetColor($color);
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

function formatMatrix(array &$matrix): void
{
    foreach ($matrix as &$row) {
        foreach ($row as &$cell) {
            $cell = sprintf(FMT, $cell);
        }
    }
}

// Полином Лагранжа
$coefs = getLagrangeCoefficients($x, $y);
$lagrFormula = getLagrangeFormula($coefs, $x);
$lagrPoints = getLagrangePoints($x[0], STEP, $x, $coefs);
$lagrResult = sprintf(FMT, lagrangeFunc($x0, $x, $coefs));
transposeMatrix($lagrPoints);

// Полином Ньютона
$dividedDiffs = getDividedDiffs($x, $y);
$newtonFormula = getNewtonFormula($dividedDiffs);
$newtonPoints = getNewtonPoints($x[0], STEP, $x, $dividedDiffs);
$newtonResult = sprintf(FMT, newtonFunc($x0, $dividedDiffs));
transposeMatrix($newtonPoints);

// Линейный сплайн
$splineCoefs = getSplineCoeffs($x, $y);
list($a, $b) = $splineCoefs;
$splineFormula = getSplineFormula($a, $b, $x);
$funcRes = splineFunc($x0, $x, $a, $b);
$splineResult = (is_null($funcRes)) ? 'error' : sprintf(FMT, $funcRes);
$splinePoints = getSplinePoints($x[0], STEP, $x, $a, $b);
transposeMatrix($splinePoints);

// Построение графиков
$lineColors = [
    'disp-lagr' => '#0056b3', 
    'disp-newt' => '#ee3b3b', 
    'disp-spl' => '#00cc00'
    ];
$datasets = [];
if (isset($_POST['disp-lagr'])) {
    $datasets[] = $lagrPoints;
} else {
    unset($lineColors['disp-lagr']);
}
if (isset($_POST['disp-newt'])) {
    $datasets[] = $newtonPoints;
} else {
    unset($lineColors['disp-newt']);
}
if (isset($_POST['disp-spl'])) {
    $datasets[] = $splinePoints;
} else {
    unset($lineColors['disp-spl']);
}
try {
    buildGraph($datasets, array_values($lineColors));
} catch (Exception $ex) {}


// Форматирование промежуточных результатов вычислений
formatMatrix($coefs);
formatMatrix($lagrPoints);
formatMatrix($dividedDiffs);
formatMatrix($newtonPoints);
formatMatrix($splineCoefs);
formatMatrix($splinePoints);

// Передача данных в шаблон
$smarty = new Smarty();
$smarty->assign('lagrFormula', $lagrFormula);
$smarty->assign('lagrResult', $lagrResult);
$smarty->assign('lagrCoeffs', $coefs);
$smarty->assign('lagrPoints', $lagrPoints);
$smarty->assign('newtonFormula', $newtonFormula);
$smarty->assign('newtonResult', $newtonResult);
$smarty->assign('dividedDiffs', $dividedDiffs);
$smarty->assign('newtonPoints', $newtonPoints);
$smarty->assign('splineCoefs', $splineCoefs);
$smarty->assign('splineFormula', $splineFormula);
$smarty->assign('splineResult', $splineResult);
$smarty->assign('splinePoints', $splinePoints);
$smarty->assign('x0', $x0);
$smarty->display('lab5.tpl');
