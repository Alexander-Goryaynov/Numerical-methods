<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" 
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
              crossorigin="anonymous">
        {literal}
            <script 
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" 
            integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" 
            crossorigin="anonymous"></script>
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
                tr:first-of-type {
                    font-weight: bold;
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

