<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" 
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
              crossorigin="anonymous">
        <title>Решение</title>
        {literal}
            <script 
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" 
            integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" 
            crossorigin="anonymous"></script>
            <style>
                h5 {
                    margin-top: 30px;
                    margin-bottom: 30px;
                }
                td:hover {
                    background-color: #ffff64
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
            <h5><u>Графики</u></h5>
            <img src="graphs/polynom.png">
            <h5><u>Формулы полиномов и значения приближений</u></h5>
            <h5>
                <div class="lagrange">
                    Полином Лагранжа
                </div>
            </h5>
            <h6>{$lagrFormula}</h6>
            <h6>Значение приближения в точке {$x0}:&nbsp;&nbsp;{$lagrResult}</h6>
            <h5>
                <div class="newton">
                    Полином Ньютона
                </div>
                <h6>{$newtonFormula}</h6>
                <h6>Значение приближения в точке {$x0}:&nbsp;&nbsp;{$newtonResult}</h6>
            </h5>
            <h5>
                <div class="spline">
                    Линейный сплайн
                </div>
                <h6>{$splineFormula}</h6>
                <h6>
                    Значение приближения в точке {$x0}:&nbsp;&nbsp;
                    {if $splineResult === 'error'}
                        <span class="newton">
                            Произошла ошибка! X<sub>o</sub> вне диапазона!
                        </span>
                    {else}
                        {$splineResult}
                    {/if}
                </h6>
            </h5>
            <h5><u>Промежуточные результаты</u></h5>
            <h5>
                <div class="lagrange">
                    Полином Лагранжа
                </div>                
            </h5>
            <h6>&bull; коэффициенты</h6>           
            <table class="table table-bordered table-sm table-hover">                 
                    {foreach from=$lagrCoeffs item=row name=n}
                        <tr>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
            </table>
            <h6>&bull; значения в точках</h6>           
            <table class="table table-bordered table-sm table-hover">                 
                    {foreach from=$lagrPoints item=row name=n}
                        <tr>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
            </table>
            <h5>
                <div class="newton">
                    Полином Ньютона
                </div>                
            </h5>
            <h6>&bull; разделённые разности</h6>           
            <table class="table table-bordered table-sm table-hover">                 
                    {foreach from=$dividedDiffs item=row name=n}
                        <tr>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
            </table>
            <h6>&bull; значения в точках</h6>           
            <table class="table table-bordered table-sm table-hover">                 
                    {foreach from=$newtonPoints item=row name=n}
                        <tr>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
            </table>
            <h5>
                <div class="spline">
                    Линейный сплайн
                </div>                
            </h5>
            <h6>&bull; коэффициенты</h6>
            <table class="table table-bordered table-sm table-hover">                 
                    {foreach from=$splineCoefs item=row name=n}
                        <tr>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
            </table>
            <h6>&bull; значения в точках</h6>           
            <table class="table table-bordered table-sm table-hover">                 
                    {foreach from=$splinePoints item=row name=n}
                        <tr>
                            {foreach from=$row item=cell}
                                <td>
                                    {$cell}
                                </td>
                            {/foreach}
                        </tr>                        
                    {/foreach}
            </table>
        </div>        
    </body>
</html>

