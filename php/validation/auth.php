<?php
require("../connect.php");

function sanitize_string($text)
{
    return htmlentities(filter_var(trim($text), FILTER_DEFAULT));
}
if ($connect->connect_errno) {
    echo "Ошибка" . $connect->connect_error;
} else {
    $email = sanitize_string($_POST["email"]);
    $password = sanitize_string($_POST["password"]);

    if (strlen($email) > 0 & strlen($password) > 0) {
        $user = $connect->query("SELECT * FROM `users` WHERE `email` = '$email' AND `password` =  '$password'")->fetch_assoc();
        if (!empty($user)) {
            setcookie("user_id",$user["user_id"],time() + (86400*30)*12,"/");
        } else {
            setcookie("invalid-length-auth","Проверьте правильность введеных данных",time() + 10,'/');
        }
    } else {
        setcookie("invalid-length-auth","Проверьте длину введеных данных",time() + 10,'/');
    }
}
$connect->close();
header("Location: ../../index.php");