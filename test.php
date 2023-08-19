<?php

header("location: index.php");
die();

require_once "core/DB.php";

$db = new DB();

$rdate = "2023-07-16";

$records = $db->findAll("select * from user_detail");

foreach ($records as $record) {
    $db->save("insert into readings values (:cid, :rdate, :unit)", [
        ":cid" => $record["cid"],
        ":rdate" => $rdate,
        ":unit" => rand(20, 50)
    ]);
}

echo "success";
