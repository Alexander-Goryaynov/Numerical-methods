<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" 
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
              crossorigin="anonymous">
        <script 
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" 
            integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" 
            crossorigin="anonymous"></script>
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
            <h5>Разностный метод Ньютона с заданным шагом h</h5>
            <?php
            $eps = floatval(htmlentities($_POST['epsilon']));
            $x = (int)(htmlentities($_POST['x']));
            $h = floatval(htmlentities($_POST['h']));
            include 'lab3Logic.php';
            start($eps, $x, $h);
            ?>
        </div>        
    </body>
</html>
