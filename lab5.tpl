<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css">
        <title>Решение</title>
        {literal}
            <script src="bootstrap.bundle.js"></script>
            <style>
                h5 {
                    margin-top: 30px;
                    margin-bottom: 30px;
                }
                td:hover {
                    background-color: #ffff64
                }
                tr:first-of-type {
                    font-weight: bold;
                }
                img {
                    cursor: crosshair;
                }
                .lagrange {
                    color: #0056b3;
                }
                .newton {
                    color: #ee3b3b;
                }
                .spline {
                    color: #00cc00;
                }
            </style>
        {/literal}
    </head>
    <body>        
        <div class="container py-2">
            <h5>Графики</h5>
            <img src="graphs/polynom.png">
            <h5>
                <div class="lagrange">
                    Полином Лагранжа
                </div>
            </h5>
            <h6>{$lagrFormula}</h6>
            <h6>Значение приближения в точке {$x0}: {$lagrResult}</h6>
            <!--
            <table class="table table-bordered table-hover display-5">
                <tbody>
                    <tr>
                        <td>Номер</td>
                        <td>x1</td>
                        <td>x2</td>
                        <td>Ф(x1)</td>
                        <td>Ф(x2)</td>
                        <td>Норма</td>
                    </tr>
                    {foreach from=$tab item=row name=n}
                        <tr>
                            <td>
                                {*Получение номера строки из цикла foreach*}
                                {$smarty.foreach.n.index}
                            </td>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
                </tbody>
            </table>
            -->
            <h5>
                <div class="newton">
                    Полином Ньютона
                </div>
                <h6>Значение приближения в точке {$x0}: {$newtonResult}</h6>
            </h5>
            <h5>
                <div class="spline">
                    Линейный сплайн
                </div>
            </h5>
        </div>        
    </body>
</html>

