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
        <div class="container py-5">
            <?php
            include 'lab2Logic.php';
            $eps = floatval(htmlentities($_POST['epsilon']));
            $a = [
                [5.401, 0.519, 0.364, 0.283],
                [0.295, 4.83, 0.421, 0.278],
                [0.524, 0.397, 4.723, 0.389],
                [0.503, 0.264, 0.248, 4.286]
            ];
            $b = [0.243, 0.231, 0.721, 0.22];
            $solver = new RelaxationSolver($a, $b, $eps);
            $solver->start();
            ?>
        </div>        
    </body>
</html>
