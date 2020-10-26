<?php

class RelaxationSolver
{

    private array $a;
    private array $b;
    private array $n;
    private array $x;
    private array $r;
    private float $eps;
    private string $roundFormatStr;

    public function __construct(array $a, array $b, float $eps)
    {
        $this->a = $a;
        $this->b = $b;
        $this->eps = $eps;
        $this->r = array();
        $this->x = array();
        $accuracy = - (int)log10($this->eps);
        $this->roundFormatStr = '%01.' . (string)$accuracy . 'f';
    }

    public function start(): void
    {
        echo '<h5>Матрица А</h5>';
        $this->displayMatrix($this->a);
        echo '<h5>Вектор В</h5>';
        $this->displayMatrix($this->b, true);
        echo '<h5>Результаты преобразования</h5>';
        $this->diagonalDivide($this->a, $this->b);
        $this->displayMatrix($this->a);
        $this->displayMatrix($this->b, true);
        echo '<h5>Матрица невязок</h5>';
        $this->createResidualMatrix();
        $this->displayMatrix($this->n);
        echo '<h5>Итерации</h5>';
        $this->makeIterations($this->n, $this->x, $this->r);
        echo '<h6>Выполнено итераций:  ' . count($this->x) . '</h6>';
        echo '<div class="row"><div class="col-sm-6">';
        $this->displayMatrix($this->x);
        echo '</div><div class="col-sm-6">';
        $this->displayMatrix($this->r);
        echo '</div></div>';
        echo '<h5>Результат</h5>';
        $this->displayMatrix($this->x[count($this->x) - 1], true);
    }

    private function diagonalDivide(array &$matrix, array &$vector): void
    {
        for ($i = 0; $i < count($matrix); $i++) {
            $divider = $matrix[$i][$i];
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                $matrix[$i][$j] /= $divider;
            }
            $vector[$i] /= $divider;
        }
    }

    private function createResidualMatrix(): void
    {
        $this->n = array();
        foreach ($this->b as $elem) {
            $this->n[] = array($elem);
        }
        for ($i = 0; $i < count($this->a); $i++) {
            for ($j = 0; $j < count($this->a[$i]); $j++) {
                if ($this->a[$i][$j] == 1) {
                    continue;
                }
                $this->n[$i][] = - $this->a[$i][$j];
            }
        }
    }

    private function makeIterations(&$n, &$x, &$r): void
    {
        $x = [[0, 0, 0, 0]];
        $r = [];
        $i = 0;
        while (true) {
            $newResidRow = [
                $n[0][0] - $x[$i][0] + $n[0][1] * $x[$i][1] + $n[0][2] * $x[$i][2] + $n[0][3] * $x[$i][3],
                $n[1][0] - $x[$i][1] + $n[1][1] * $x[$i][0] + $n[1][2] * $x[$i][2] + $n[1][3] * $x[$i][3],
                $n[2][0] - $x[$i][2] + $n[2][1] * $x[$i][0] + $n[2][2] * $x[$i][1] + $n[2][3] * $x[$i][3],
                $n[3][0] - $x[$i][3] + $n[3][1] * $x[$i][0] + $n[3][2] * $x[$i][1] + $n[3][3] * $x[$i][2]
            ];
            $r[$i] = $newResidRow;
            if ($this->checkFinish($newResidRow)) {
                break;
            }
            $maxIndex = $this->getMaxAbsElemIndex($newResidRow);
            $x[$i + 1] = $x[$i];
            $x[$i + 1][$maxIndex] += $newResidRow[$maxIndex];
            $i++;
        }
    }

    private function checkFinish(array $row): bool
    {
        foreach ($row as $resid) {
            if (abs($resid) > $this->eps) {
                return false;
            }
        }
        return true;
    }

    private function getMaxAbsElemIndex(array $arr): int
    {
        $max = abs($arr[0]);
        $maxIndex = 0;
        for ($i = 1; $i < 4; $i++) {
            if (abs($arr[$i]) > $max) {
                $max = abs($arr[$i]);
                $maxIndex = $i;
            }
        }
        return $maxIndex;
    }

    private function displayMatrix(array $matrix, bool $isVector = false): void
    {
        echo '<table class="table table-bordered table-hover"><tbody>';
        for ($i = 0; $i < count($matrix); $i++) {
            echo '<tr>';
            if ($isVector) {
                echo '<td>' . sprintf('%01.4f', $matrix[$i]) . '</td>';
            } else {
                for ($j = 0; $j < count($matrix[$i]); $j++) {                    
                    echo '<td>' . sprintf($this->roundFormatStr, $matrix[$i][$j]) . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

}
