<?php

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function response($status, $value)
{
    switch ($status) {
        case "success":
            $status = "success";
            break;
        default:
            $status = "error";
            break;
    }

    return json_encode([
        "status" => $status,
        "value" => $value
    ]);
}

function not_empty($value = null)
{
    if (isset($value)) {
        if (!empty($value)) {
            return true;
        }
    }
    return false;
}
