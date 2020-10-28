<?php

function displayThead(): void
{
    echo '<table class="table table-bordered table-hover display-5"><tbody>';
    echo '<tr>';
    echo '<td><h2>' . 'n' . '</h2></td>';
    echo '<td><h2>' . 'x<sub>n</sub>' . '</h2></td>';
    echo '<td><h2>' . 'f(x<sub>n</sub>)' . '</h2></td>';
    echo '<td><h2>' . '|x<sub>n</sub>-x<sub>n-1</sub>|' . '</h2></td>';
    echo '</tr>';
}

function displayMatrix(array $matrix, $eps): void {
    $accuracy = - (int)log10($eps);
    $roundFormatStr = '%01.' . (string)$accuracy . 'f';
    for ($i = 0; $i < count($matrix); $i++) {
        echo '<tr>';
        for ($j = 0; $j < count($matrix[$i]); $j++) {
            if ($j === 0) {
                echo '<td>' . (string)(int)$matrix[$i][$j] . '</td>';
            } else {
                echo '<td>' . sprintf($roundFormatStr, $matrix[$i][$j]) . '</td>';
            }            
        }
        echo '</tr>';
    }
    echo '</tbody></table>';
}

function displayAnswer(float $number, $eps): void {
    $accuracy = - (int)log10($eps);
    $roundFormatStr = '%01.' . (string)$accuracy . 'f';
    echo '<h5>Результат</h5>';
    echo '<h5>' . sprintf($roundFormatStr, $number) . '</h5>';
}

function start($eps): void
{
    displayThead();
    // шаг
    $h = 0.001;
    // функция по варианту
    $f = fn(float $x): float => 1 - $x * log($x) + 0.3 * sqrt($x);
    // функция расчёта Xn+1
    $g = fn(float $x): float => $x - ($f($x) * $h) / ($f($x + $h) - $f($x));
    $i = 0;
    $tab = array();
    while (true) {
        if ($i === 0) {
            $tab[0][0] = 0;
            $tab[0][1] = 3;
            $tab[0][2] = $f($tab[0][1]);
            $i++;
            continue;
        }
        $tab[$i][0] = $i;
        $tab[$i][1] = $g($tab[$i - 1][1]);
        $tab[$i][2] = $f($tab[$i][1]);
        $tab[$i][3] = abs($tab[$i][1] - $tab[$i - 1][1]);
        if ($tab[$i][3] < $eps) {
            displayMatrix($tab, $eps);
            displayAnswer($tab[$i][1], $eps);
            break;
        }
        $i++;
    }
}
