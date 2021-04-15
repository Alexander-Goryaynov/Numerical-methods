<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Горяйнов л.р. 5</title>
    {literal}
    <style>
        html {
            width: 100%;
            height: 100%;
        }
        body {
            width: 100%;
            height: 100%;
            background: linear-gradient(to top left, #d391f4, #8ef8fd) no-repeat fixed;
            overflow-x: hidden;
            font-family: Consolas, serif;
        }
        body>div {
            margin-left: 100px;
            margin-top: 5%;
        }
    </style>
    {/literal}
</head>
<body>
<div>
    {foreach from=$resultLines item=line}
        {$line}<br>
    {/foreach}
</div>
</body>
</html>
