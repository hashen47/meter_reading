<?php
require_once "core/DB.php";
require_once "core/functions.php";

$db = new DB();

session_start();

function validate()
{
    global $db;

    // check wheter username and password is empty
    if (
        not_empty($_POST["username"]) &&
        not_empty($_POST["password"])
    ) {
        // check whether username exists 
        if (
            $db->rowCount("select * from login where name=:name", [":name" => $_POST["username"]]) > 0
        ) {
            // check whether username and password combination is valid
            if (
                $db->rowCount(
                    "select * from login where name=:name and psw=:psw",
                    [
                        ":name" => $_POST["username"],
                        ":psw" => $_POST["password"]
                    ]
                ) == 0
            ) {
                echo response("error", "username and password combination is invalid");
                die();
            }
        } else {
            echo response("error", "invalid username");
            die();
        }
    } else {
        echo response("error", "username or password is emtpy");
        die();
    }
}

function loging()
{
    global $db;
    $_SESSION["uid"] = $db->find("select * from login where name=:name and psw=:psw", [
        ":name" => $_POST["username"],
        ":psw" => $_POST["password"]
    ])["id"];
    echo response("success", "successfully logged in");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    validate();
    loging();
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- defualt css links -->
    <?php require_once "core/Css.php"; ?>
    <!-- /defualt css links -->
    <title>Login</title>
</head>

<body>
    <?php require_once "core/Lang.php"; ?>
    <?php if ($lang == "eng") : ?>
        <div class="container">
            <div>
                <button>
                    <a href="index.php">Home</a>
                </button>
            </div>
            <div>
                <h1>Login</h1>
            </div>
            <div>
                <div>
                    <label>Username</label>
                    <input name="username" id="username" />
                </div>
                <div>
                    <label>Password</label>
                    <input name="password" id="password" />
                </div>
                <div>
                    <button onclick="login();">Login</button>
                </div>
            </div>
        </div>
    <?php elseif ($lang == "sin") : ?>
        <div class="container">
            <div>
                <button>
                    <a href="index.php">හෝම් පෙජය</a>
                </button>
            </div>
            <div>
                <h1>ලොග් වීම</h1>
            </div>
            <div>
                <div>
                    <label>නම</label>
                    <input name="username" id="username" />
                </div>
                <div>
                    <label>මුරපදය</label>
                    <input name="password" id="password" />
                </div>
                <div>
                    <button onclick="login();">ලොග් වන්න</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
<!-- default js links -->
<?php require_once "core/Js.php"; ?>
<!-- /default js links -->
<script defer src="static/js/login.js"></script>

</html>