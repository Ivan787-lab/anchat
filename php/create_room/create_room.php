<?php
require("../connect.php");
$user_id = $_POST["user_id"];
$dialog_id = rand(10000000000,99999999999);

$connect->query("INSERT `dialogs` (`admin_id`,`dialog_id`) VALUES ('$user_id','$dialog_id')");

header("Location: ../../chat.php?admin_id=$user_id&dialog_id=$dialog_id");
$connect->close();