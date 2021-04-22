<?php
require_once __DIR__ . '/../smarty-3/libs/Smarty.class.php';
require_once __DIR__ . '/Algorithm.php';


function processRequestParams(&$left, &$right, &$accuracy, &$count): void
{
    if (isset($_GET['left'])) {
        $left = (float)htmlspecialchars($_GET['left']);
    }
    if (isset($_GET['right'])) {
        $right = (float)htmlspecialchars($_GET['right']);
    }
    if (isset($_GET['accuracy'])) {
        $accuracy = (float)htmlspecialchars($_GET['accuracy']);
    }
    if (isset($_GET['count'])) {
        $count = (int)htmlspecialchars($_GET['count']);
    }
}

function displayResults(array $lines)
{
    $smarty = new Smarty();
    $smarty->assign('resultLines', $lines);
    $smarty->display('lab5.tpl');
}

function f(float $x): float
{
    return 10 * $x * log($x) - $x ** 2 / 2;
}

function d1(float $x): float
{
    return -$x + 10 * log($x) + 10;
}

function d2(float $x): float
{
    return 10 / $x - 1;
}

/**
 * Округляет число согласно указанной точности
 */
function rndAc(float $number, float $accuracy): float
{
    return round($number, -log($accuracy, 10));
}

function svenn(float $x0, float $h, array &$res): array
{
    $f1 = f($x0 - $h);
    $f2 = f($x0);
    $f3 = f($x0 + $h);
    $res[] = 'Алгоритм поиска интервала методом Свенна:';
    $res[] = 'f(x0-h) = ' . round($f1, 3) . '; f(x0) = ' . round($f2, 3) .
        '; f(x0+h) = ' . round($f3, 3);
    $d = null;
    $a0 = null;
    $b0 = null;
    $x1 = null;
    if ($f1 >= $f2 && $f2 <= $f3) {
        $left = $x0 - $h;
        $right = $x0 + $h;
        $res[] = "f(x0-h) >= f(x0) <= f(x0+h) следовательно РЕЗУЛЬТАТ = [$left, $right]";
        $res[] = '';
        return [$left, $right];
    } elseif ($f1 <= $f2 && $f2 >= $f3) {
        $res[] = 'Невозможно вычислить интервал неопределённости';
        return [0, 0];
    }
    if ($f1 >= $f2 && $f2 >= $f3) {
        $d = $h;
        $a0 = $x0;
        $x1 = $x0 + $h;
        $res[] = "f(x0-h) >= f(x0) >= f(x0+h) следовательно d = $d, a0 = $a0, x1 = $x1";
    } elseif ($f1 <= $f2 && $f2 <= $f3) {
        $d = -$h;
        $b0 = $x0;
        $x1 = $x0 - $h;
        $res[] = "f(x0-h) <= f(x0) <= f(x0+h) следовательно d = $d, b0 = $b0, x1 = $x1";
    }
    $k = 1;
    $res[] = "k = $k";
    $x_next = null;
    while (true) {
        $res[] = 'новая итерация:';
        $x_next = $x1 + 2 ** $k * $d;
        $res[] = "x(k+1) = $x_next";
        if (f($x_next) < f($x1)) {
            if ($d == $h) {
                $a0 = $x1;
            } elseif ($d == -$h) {
                $b0 = $x1;
            }
            $k++;
            $res[] = "f(x(k+1)) < f(x(k)) следовательно k = $k, a0 = $a0, b0 = $b0";
        } else {
            if ($d == $h) {
                $b0 = $x_next;
            } elseif ($d == -$h) {
                $a0 = $x_next;
            }
            $res[] = "f(x(k+1)) >= f(x(k)) следовательно РЕЗУЛЬТАТ = [$a0, $b0]";
            $res[] = '';
            return [$a0, $b0];
        }
    }
}

function uniform(float $a, float $b, $n, float $acc, array &$res): void
{
    $res[] = 'Метод равномерного поиска:';
    $n ??= 1000;
    $h = ($b - $a) / $n;
    $xMin = $a;
    $yMin = f($a);
    $res[] = "n = $n; h = $h; xMin = $xMin; yMin = $yMin";
    $i = 0;
    for ($x = $a + $h; $x <= $b; $x += $h) {
        $res[] = "Итерация № $i: x = $x";
        $y = f($x);
        $res[] = "у = $y";
        if ($y < $yMin) {
            $xMin = $x;
            $yMin = $y;
            $res[] = "y < yMin, следовательно xMin = $xMin, yMin = $yMin";
        }
        $i++;
    }
    $res[] = 'РЕЗУЛЬТАТ работы метода равномерного поиска: минимум ф-ии находится в точке (' . rndAc($xMin, $acc) .
        ';' . rndAc($yMin, $acc) . ')';
}

function newton(float $start, float $eps, array &$res): void
{
    $res[] = 'Метод Ньютона:';
    $x1 = null;
    $dx = null;
    $x0 = $start;
    $i = 0;
    do {
        $res[] = "Итерация № $i";
        $x1 = $x0 - d1($x0) / d2($x0);
        $dx = abs(d1($x1));
        $x0 = $x1;
        $res[] = 'x1 = ' . rndAc($x1, $eps) . '; dx = ' . rndAc($dx, $eps) . '; x0 = ' . rndAc($x0, $eps);
        $i++;
    } while ($dx > $eps);
    $res[] = 'Конец цикла';
    $res[] = "d''(x1) = " . rndAc(d2($x1), $eps);
    if (d2($x1) > 0) {
        $res[] = 'РЕЗУЛЬТАТ работы метода Ньютона: минимум ф-ии находится в точке (' . rndAc($x1, $eps) .
            ';' . rndAc(f($x1), $eps) . ')';
    } else {
        $res[] = 'Экстремум на этом интервале не является минимумом';
    }
}

function halfDivision(float $a, float $b, float $eps, array &$res): void
{
    // шаг 1
    $res[] = 'Метод половинного деления:';
    $res[] = "Задали начальный интервал неопределённости L = [$a, $b]";
    $a = [$a];
    $b = [$b];
    $xc = [];
    $l2 = [];
    $y = [];
    $z = [];
    // шаг 2
    $k = 0;
    $res[] = "k = $k";
    while (true) {
        // шаг 3
        $xc[$k] = ($a[$k] + $b[$k]) / 2;
        $l2[$k] = $b[$k] - $a[$k];
        $res[] = "xc[k] = $xc[$k]; l2[k] = $l2[$k]";
        // шаг 4
        $y[$k] = $a[$k] + abs($l2[$k]) / 4;
        $z[$k] = $b[$k] - abs($l2[$k]) / 4;
        $res[] = "y[k] = $y[$k]; z[k] = $z[$k]";
        // шаг 5
        if (f($y[$k]) < f($xc[$k])) {
            $res[] = "f(y[k]) < f(xc[k]), тогда";
            $b[$k + 1] = $xc[$k];
            $a[$k + 1] = $a[$k];
            $xc[$k + 1] = $y[$k];
            $res[] = "b[k+1] = {$b[$k + 1]}, a[k+1] = {$a[$k + 1]}, xc[k+1] = {$xc[$k + 1]}";
        } else {
            $res[] = "f(y[k]) >= f(xc[k]), тогда";
            // шаг 6
            if (f($z[$k]) < f($xc[$k])) {
                $res[] = "f(z[k]) < f(xc[k]), тогда";
                $a[$k + 1] = $xc[$k];
                $b[$k + 1] = $b[$k];
                $xc[$k + 1] = $z[$k];
                $res[] = "b[k+1] = {$b[$k + 1]}, a[k+1] = {$a[$k + 1]}, xc[k+1] = {$xc[$k + 1]}";
            } else {
                $res[] = "f(z[k]) >= f(xc[k]), тогда";
                $a[$k + 1] = $y[$k];
                $b[$k + 1] = $z[$k];
                $xc[$k + 1] = $xc[$k];
                $res[] = "b[k+1] = {$b[$k + 1]}, a[k+1] = {$a[$k + 1]}, xc[k+1] = {$xc[$k + 1]}";
            }
        }
        // шаг 7
        $l2[$k + 1] = abs($b[$k + 1] - $a[$k + 1]);
        $res[] = "l2[k+1] = {$l2[$k+1]}";
        if (abs($l2[$k + 1]) <= $eps) {
            $res[] = "l2[k+1] < &#949;";
            $res[] = 'РЕЗУЛЬТАТ работы метода половинного деления: минимум ф-ии находится в точке (' .
                rndAc($xc[$k + 1], $eps) . ';' . rndAc(f($xc[$k + 1]), $eps) . ')';
            return;
        } else {
            $k++;
            $res[] = "k = $k";
            $res[] = "новая итерация";
        }
    }
}

$method = $_GET['method'];
// TODO заменить поле $right на $h
$left = null;
$right = null;
$accuracy = null;
$count = null;
processRequestParams($left, $right, $accuracy, $count);
$resultLines = [];
// TODO вводить для алгоритма Свенна параметры $x0(т.е. $left) и $h с клавиатуры
[$left, $right] = svenn(0.2, 0.1, $resultLines);
switch ($method) {
    case Algorithm::UNIFORM:
        uniform($left, $right, $count, $accuracy, $resultLines);
        break;
    case Algorithm::NEWTON:
        newton($left, $accuracy, $resultLines);
        break;
    case Algorithm::HALF_DIVISION:
        halfDivision($left, $right, $accuracy, $resultLines);
        break;
}
displayResults($resultLines);
