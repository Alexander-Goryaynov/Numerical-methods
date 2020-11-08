<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css">
        {literal}
            <script src="bootstrap.bundle.js"></script>
        {/literal}
        <title>Решение</title>
        {literal}
            <style>
                h5 {
                    margin-top: 30px;
                    margin-bottom: 30px;
                }
                td:hover {
                    background-color: #ffff64
                }
            </style>
        {/literal}
    </head>
    <body>        
        <div class="container py-2">
            <h5>Метод наискорейшего спуска</h5>
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
        </div>        
    </body>
</html>

