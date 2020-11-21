<?php

function getSplineCoeffs(array $x, array $y): array
{
    $a = [];
    $b = [];
    for ($i = 0; $i < count($x) - 1; $i++) {
        $a[$i] = ($y[$i + 1] - $y[$i]) / ($x[$i + 1] - $x[$i]);
        $b[$i] = $y[$i] - $a[$i] * $x[$i];
    }
    return [$a, $b];
}

function getSplineFormula(array $a, array $b, array $x): string
{
    $res = '';
    $a = array_map(fn(&$x)=> sprintf(FMT, $x), $a);
    $b = array_map(fn(&$x)=> sprintf(FMT, $x), $b);
    for($i = 1; $i < count($x); $i++) {
        $res .= $a[$i - 1] . ' &#8226; X + ' . $b[$i - 1] . ',&nbsp;&nbsp;&nbsp;' .
                $x[$i - 1] . ' &le; X &le; ' . $x[$i];
        $res .= '<br>';
    }
    return $res;
}

function splineFunc(float $x0, array $x, array $a, array $b): ?float
{
    for ($i = 0; $i < count($x) - 1; $i++) {
        if (($x0 > $x[$i] || abs($x0 - $x[$i] < PHP_FLOAT_EPSILON)) && $x0 < $x[$i + 1]) {
            return $a[$i] * $x0 + $b[$i]; 
        }
    }
    return null;
}

function getSplinePoints(float $xStart, float $h, array $x, array $a, array $b): array
{
    $s = [];
    $s[0][0] = $xStart;
    $i = 0;
    while (true) {
        if ($i !== 0) {
            $s[$i][0] = $s[$i - 1][0] + $h;
        }
        $funcRes = splineFunc($s[$i][0], $x, $a, $b);
        if (is_null($funcRes)) {
            unset($s[$i][0]);
            return $s;
        } else {
            $s[$i][1] = $funcRes;
        }        
        $i++;
    }
}