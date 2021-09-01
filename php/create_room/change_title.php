<?php
require("../connect.php");

function sanitize_string($text)
{
    return htmlentities(filter_var(trim($text), FILTER_DEFAULT));
}


if ($connect->connect_errno) {
    echo "Ошибка" . $connect->connect_error;
} else {
    $dialog_title = sanitize_string($_POST["dialog_title"]);
    $admin_id = $_POST["admin_id"];
    $dialog_id = $_POST["dialog_id"];
    echo $dialog_id;

    if (strlen($dialog_title) > 0) {
        $connect->query("UPDATE `dialogs` SET `dialog_title` = '$dialog_title' WHERE `dialog_id` = '$dialog_id'");
        setcookie("small-length-title", "", time() + 10, '/');
    } else {
        setcookie("small-length-title", "Заголовок слишком короткий", time() + 10, '/');
    }
}
header("Location: ../../chat.php?admin_id=$admin_id&dialog_id=$dialog_id");
$connect->close();
