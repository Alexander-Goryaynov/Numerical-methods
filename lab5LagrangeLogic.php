<?php

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
        $coef = $p[$i][2];
        if ($coef >= 0) {
            $res .= sprintf(FMT, $coef);
        } else {
            $res .= '(' . sprintf(FMT, $coef) . ')';
        }
        for ($j = 0; $j < count($x); $j++) {
            if ($j === $i) {
                continue;
            }
            $res .= ' &#8226; (X - ' . sprintf(FMT, $x[$j]) . ')';
        }
        if ($i !== count($p) - 1) {
            $res .= ' + ';
        }
    }
    return $res;
}