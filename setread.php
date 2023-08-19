<?php
require "core/DB.php";
require "core/functions.php";

// db connection
$db = new DB();

function validate()
{
    global $db;

    // vlidate date
    try {
        $d = date("Y-m-d", strtotime($_POST["date"]));
    } catch (Exception $e) {
        echo response("error", "invalid date");
    }

    // validate unit
    if (!strcmp("0", $_POST["reading"])) {
        if ((int)$_POST["reading"] == 0 || (int)$_POST["reading"] < 0) {
            echo response("error", "invalid reading input");
            die();
        }
    }

    // validate cid
    if (
        $db->rowCount("select * from user_detail where cid=:cid", [":cid" => $_POST["cid"]]) > 0
    ) {
        // already have a record to current date
        if (
            $db->rowCount("select * from readings where cid=:cid and rdate=:rdate", [
                ":cid" => $_POST["cid"],
                ":rdate" => $_POST["date"]
            ]) > 0
        ) {
            echo response("error", "already exists");
            die();
        }
    } else {
        echo response("error", "unknown customer id...!");
        die();
    }

    // check whether date value is a backdate
    if (
        $db->find(
            "select * from readings where cid=:cid order by rdate desc",
            [":cid" => $_POST["cid"]]
        )["rdate"] >= $_POST["date"]
    ) {
        echo response("error", "canno't added readings to previous dates");
        die();
    }
}

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $cids = $db->findAll("select * from user_detail");
        break;
    case "POST":
        // check data is empty or not
        if (
            not_empty($_POST["cid"]) &&
            not_empty($_POST["date"]) &&
            not_empty($_POST["reading"])
        ) {
            // validate data
            validate();

            // save the data
            $db->save("insert into readings values (:cid, :rdate, :unit)", [
                ":cid" => $_POST["cid"],
                ":rdate" => $_POST["date"],
                ":unit" => $_POST["reading"]
            ]);
            echo response("success", "Data added successfully");
        } else {
            echo response("error", "All fields are required");
        }
        die();
        break;
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>කියවීම් සකසන්න</title>
    <!-- defualt css links -->
    <?php require_once "core/Css.php"; ?>
    <!-- /defualt css links -->
</head>

<body>
    <div class="container">
        <?php require_once "core/Lang.php"; ?>
        <!-- for english language -->
        <?php if ($lang == "eng") : ?>
            <div>
                <button>
                    <a href="index.php">Home</a>
                </button>
            </div>
            <div>
                <h1>Set Reading</h1>
            </div>
            <div>
                <div class="form-group">
                    <div>
                        <label>Date</label>
                        <input type="date" id="date" class="" />
                    </div>
                    <div>
                        <label>Reading</label>
                        <input type="number" id="reading" class="" min=0 />
                    </div>
                    <div>
                        <label>Customer Id</label>
                        <input id="cid" class="" list="cid-list" />
                        <datalist id="cid-list">
                            <?php
                            // cid list goes here
                            foreach ($cids as $cid) {
                                echo "<option value='$cid[cid]' />";
                            }
                            ?>
                        </datalist>
                    </div>
                    <div>
                        <button onclick="reset();">reset</button>
                        <button onclick="save_data();">save</button>
                    </div>
                </div>
            </div>
        <?php elseif ($lang == "sin") : ?>
            <!-- for sinhala language -->
            <div>
                <button>
                    <a href="index.php">හෝම් පෙජය</a>
                </button>
            </div>
            <div>
                <h1>කියවීම් එකතු කරන්න</h1>
            </div>
            <div>
                <div class="form-group">
                    <div>
                        <label>දිනය</label>
                        <input type="date" id="date" class="" />
                    </div>
                    <div>
                        <label>කියවීම</label>
                        <input type="number" id="reading" class="" min=0 />
                    </div>
                    <div>
                        <label>පාරිභෝගික හැඳුනුම් පත</label>
                        <input id="cid" class="" list="cid-list" />
                        <datalist id="cid-list">
                            <?php
                            // cid list goes here
                            foreach ($cids as $cid) {
                                echo "<option value='$cid[cid]' />";
                            }
                            ?>
                        </datalist>
                    </div>
                    <div>
                        <button onclick="reset();">මකන්න</button>
                        <button onclick="save_data();">සුරකින්න</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
<!-- default js links -->
<?php require_once "core/Js.php"; ?>
<!-- /default js links -->

<script src="static/js/setread.js"></script>

</html>