<?php

function newtonFunc(float $x, array $d): float
{
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
    while (true) {
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

function getNewtonFormula(array $d): string
{
    $res = "";
    for ($i = 0; $i < count($d); $i++) {
        $coef = $d[0][$i + 1];
        if ($coef >= 0) {
            $res .= sprintf(FMT, $coef);
        } else {
            $res .= '(' . sprintf(FMT, $coef) . ')';
        }
        for ($j = 0; $j < $i; $j++) {
            $res .= ' &#8226; (X - ' . sprintf(FMT, $d[$j][0]) . ')';
        }
        if ($i !== count($d) - 1) {
            $res .= ' + ';
        }
    }
    return $res;
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
