<?php
require_once __DIR__ . '/jpgraph-4/src/jpgraph.php';
require_once __DIR__ . '/jpgraph-4/src/jpgraph_line.php';

$graph = new Graph(1200, 700);
// Дробные значения на осях
$graph->SetScale('linlin');
// Название графика
$graph->title->Set('Квадратичный сплайн');
// Названия осей.
$graph->xaxis->title->Set('x');
$graph->yaxis->title->Set('y');

$xStart = 0.357;
$xMiddle = 1.567;
$xEnd = 2.628;
$step = 0.1;

$dataX = [];
$dataY = [];
$x = $xStart;
while ($x <= $xEnd) {
    $dataX[] = $x;
    if ($x <= $xMiddle) {
        $dataY[] = -0.573 * $x ** 2 + 1.607 * $x + 0.047;
    } else {
        $dataY[] = -0.952 * $x ** 2 + 2.427 * $x - 0.307;
    }
    $x += $step;
}

$plot = new LinePlot($dataY, $dataX);        
$graph->Add($plot);
$graph->Stroke();