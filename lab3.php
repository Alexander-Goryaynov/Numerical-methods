<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css">
        <script src="bootstrap.bundle.js"></script>
        <title>Решение</title>
        <style>
            h5 {
                margin-top: 30px;
            }
            td:hover {
                background-color: #ffff64
            }
        </style>
    </head>
    <body>        
        <div class="container py-2">
            <h5>Разностный метод Ньютона с постоянным шагом</h5>
            <h5>h = 0,001</h5>
            <?php
            $eps = floatval(htmlentities($_POST['epsilon']));            
            include 'lab3Logic.php';
            start($eps);
            ?>
        </div>        
    </body>
</html>
