<?php
require_once "core/DB.php";
require_once "core/functions.php";

$db = new DB();

function validate()
{
    global $db;

    // check if cid is empty
    if (
        !not_empty($_POST["cid"])
    ) {
        echo response("error", "cid is empty");
        die();
    }

    // check if cid is valid
    if (
        $db->rowCount("select * from user_detail where cid=:cid", [":cid" => $_POST["cid"]]) > 0
    ) {
        // check if cid has minimum 2 records
        if (
            $db->rowCount("select * from readings where cid=:cid", [":cid" => $_POST["cid"]]) < 2
        ) {
            echo response("error", "minimum 2 records needed");
            die();
        }
    } else {
        echo response("error", "invalid customer id");
        die();
    }
}


function cal_readings()
{
    global $db;

    $data = [];
    $cid = $_POST["cid"];

    $records = $db->findAll("select * from readings where cid=:cid order by rdate desc", [":cid" => $cid]);

    // date and unit difference of last two records
    $date_difference = date_diff(
        date_create($records[0]["rdate"]),
        date_create($records[1]["rdate"])
    )->days;
    $unit_difference = (int)$records[0]["unit"] - (int)$records[1]["unit"];

    // dd([$date_difference, $unit_difference]);

    $ranges = [
        "one" => [
            "unitp" => 20,
            "fix" => 500
        ],
        "two" => [
            "unitp" => 35,
            "fix" => 1000
        ],
        "three" => [
            "unitp" => 40,
            "fix" => 1500
        ]
    ];

    $fixed_amount = 0;
    $firstRBill = 0;
    $secondRBill = 0;
    $thirdRBill = 0;

    if ($unit_difference <= $date_difference) {
        $firstRBill = $unit_difference * $ranges["one"]["unitp"];
        $fixed_amount = $ranges["one"]["fix"];
    } else if ($unit_difference > $date_difference) {
        if ($date_difference * 2 >= $unit_difference - $date_difference) {
            $firstRBill = ($date_difference * $ranges["one"]["unitp"]);
            $secondRBill = ($unit_difference - $date_difference) * $ranges["two"]["unitp"];
            $fixed_amount = $ranges["two"]["fix"];
        } else {
            $firstRBill = ($date_difference * $ranges["one"]["unitp"]);
            $secondRBill = ($date_difference * 2 * $ranges["two"]["unitp"]);
            for ($i = 0; $i < ($unit_difference - $date_difference * 3); $i++) {
                $thirdRBill += $ranges["three"]["unitp"] + $i;
            }
            $fixed_amount = $ranges["three"]["fix"];
        }
    }

    return [
        "lr" => $records[0]["rdate"],
        "plr" => $records[1]["rdate"],
        "datediff" => $date_difference,
        "lu" => $records[0]["unit"],
        "plu" => $records[1]["unit"],
        "fix" => $fixed_amount,
        "firstRBill" => $firstRBill,
        "secondRBill" => $secondRBill,
        "thirdRBill" => $thirdRBill,
        "total" => $firstRBill + $secondRBill + $thirdRBill + $fixed_amount
    ];
}

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        validate();
        echo response("success", cal_readings());
        die();
        break;
    case "GET":
        $cids = $db->findAll("select * from user_detail");
        break;
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Readings</title>
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
                <h1>Get Readings</h1>
            </div>
            <div>
                <div class="form-group">
                    <div>
                        <label for="">Customer Id</label>
                        <input id="cid" list="cid-list" />
                        <datalist id="cid-list">
                            <?php
                            // cid list goes here
                            foreach ($cids as $cid) {
                                echo "<option value='$cid[cid]' />";
                            }
                            ?>
                        </datalist>
                        <button onclick="load_data();">Load</button>
                    </div>
                </div>
                <div>
                    <div>
                        <label>Last Reading Date</label>
                        <input id="lr" readonly />
                    </div>
                    <div>
                        <label>Previous Reading Date</label>
                        <input id="plr" readonly />
                    </div>
                    <div>
                        <label>Date Difference</label>
                        <input id="datediff" readonly />
                    </div>
                    <div>
                        <label>Last Meter Reading</label>
                        <input id="lu" readonly />
                    </div>
                    <div>
                        <label>Previous Meter Reading</label>
                        <input id="plu" readonly />
                    </div>
                    <div>
                        <label>Fixed Charged Amount</label>
                        <input id="fix" readonly />
                    </div>
                    <div>
                        <label>First Range Bill Amount</label>
                        <input id="firstRBill" readonly />
                    </div>
                    <div>
                        <label>Second Range Bill Amount</label>
                        <input id="secondRBill" readonly />
                    </div>
                    <div>
                        <label>Third Range Bill Amount</label>
                        <input id="thirdRBill" readonly />
                    </div>
                    <div>
                        <label>Total Bill Amount</label>
                        <input id="total" readonly />
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
                <h1>කියවීම් ලබා ගන්න</h1>
            </div>
            <div>
                <div class="form-group">
                    <div>
                        <label for="">පාරිභෝගික හැඳුනුම් පත</label>
                        <input id="cid" list="cid-list" />
                        <datalist id="cid-list">
                            <?php
                            // cid list goes here
                            foreach ($cids as $cid) {
                                echo "<option value='$cid[cid]' />";
                            }
                            ?>
                        </datalist>
                        <button onclick="load_data();">ලෝඩ් කරන්න</button>
                    </div>
                </div>
                <div>
                    <div>
                        <label>අවසන් කියවීමේ දිනය</label>
                        <input id="lr" readonly />
                    </div>
                    <div>
                        <label>පෙර කියවීමේ දිනය</label>
                        <input id="plr" readonly />
                    </div>
                    <div>
                        <label>දින වෙනස</label>
                        <input id="datediff" readonly />
                    </div>
                    <div>
                        <label>අවසාන මීටර කියවීම</label>
                        <input id="lu" readonly />
                    </div>
                    <div>
                        <label>පෙර මීටර් කියවීම</label>
                        <input id="plu" readonly />
                    </div>
                    <div>
                        <label>ස්ථාවර අය කළ මුදල</label>
                        <input id="fix" readonly />
                    </div>
                    <div>
                        <label>පළමු පරාස බිල් මුදල</label>
                        <input id="firstRBill" readonly />
                    </div>
                    <div>
                        <label>දෙවන පරාසයේ බිල්පත් මුදල</label>
                        <input id="secondRBill" readonly />
                    </div>
                    <div>
                        <label>තුන්වන පරාසයේ බිල්පත් මුදල</label>
                        <input id="thirdRBill" readonly />
                    </div>
                    <div>
                        <label>මුළු බිල් මුදල</label>
                        <input id="total" readonly />
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
<!-- default js links -->
<?php require_once "core/Js.php"; ?>
<!-- /default js links -->

<script src="static/js/getread.js"></script>

</html>