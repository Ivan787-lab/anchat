<?php
require("../connect.php");

function sanitize_string($text)
{
    return htmlentities(filter_var(trim($text), FILTER_DEFAULT));
}


if ($connect->connect_errno) {
    echo "Ошибка" . $connect->connect_error;
} else {
    $password = sanitize_string($_POST["password"]);
    $dialog_id = $_POST["dialog_id"];
    $admin_id = $_POST["admin_id"];
    if (strlen($password) > 0) {
        $connect->query("UPDATE `dialogs` SET `dialog_password` = '$password' WHERE `dialog_id` = '$dialog_id'");
        setcookie("small-length-password","",time() + 10,'/');
    } else {
        setcookie("small-length-password","Пароль слишком короткий",time() + 10,'/');
    }
}

$connect->close();
header("Location: ../../chat.php?admin_id=$admin_id&dialog_id=$dialog_id");
