<?php
require("../connect.php");

function sanitize_string($text)
{
    return htmlentities(filter_var(trim($text), FILTER_DEFAULT));
}


if ($connect->connect_errno) {
    echo "Ошибка" . $connect->connect_error;
} else {
    $name = sanitize_string($_POST["name"]);
    $email = sanitize_string($_POST["email"]);
    $password = sanitize_string($_POST["password"]);
    $user_id = rand(10000000000, 99999999999);
    $dialog_id = $_POST["dialog_id"];

    if (strlen($name) > 0 & strlen($email) > 0 & strlen($password) > 0) {
        $connect->query("INSERT `users` (`name`,`email`,`password`,`user_id`) VALUES ('$name','$email','$password','$user_id')");

        setcookie("user_id", $user_id, time() + (86400 * 30) * 12, '/');
    } else {
        setcookie("invalid-length-login","Проверьте длину введеных данных",time() + 10,'/');
    }
}
$connect->close();
header("Location: ../../");
