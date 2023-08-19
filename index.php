<!DOCTYPE html>
<html lang="en">

<head>
    <title>Meter Readings</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="index.css" rel="stylesheet">
    <!-- defualt css links -->
    <?php require_once "core/Css.php"; ?>
    <!-- /defualt css links -->
</head>

<body>
    <div class="container">
        <?php require_once "core/Lang.php"; ?>
        <?php if ($lang == "eng"): ?>
        <div>
            <h1>Meter Reading Program</h1>
            <div>
                <button>
                    <a href="setread.php">Set Read</a>
                </button>
                <button>
                    <a href="getread.php">Get read</a>
                </button>
            </div>
        </div>
        <?php elseif ($lang == "sin") : ?>
        <div>
            <h1>මීටර් කියවීමේ වැඩසටහන</h1>
            <div>
                <button>
                    <a href="setread.php">කියවීම සකසන්න</a>
                </button>
                <button>
                    <a href="getread.php">කියවන්න</a>
                </button>
            </div>
        </div>
        <?php endif; ?>
</body>
<!-- default js links -->
<?php require_once "core/Js.php"; ?>
<!-- /default js links -->
<script src="index.js"></script>

</html>