<?php
require("../connect.php");

function sanitize_string($text)
{
    return htmlentities(filter_var(trim($text), FILTER_DEFAULT));
}



if ($connect->connect_errno) {
    echo "Ошибка" . $connect->connect_error;
} else {
    if ($_COOKIE["user_id"] == "") {
        $name = sanitize_string($_POST["name"]);
        $email = sanitize_string($_POST["email"]);
        $password = sanitize_string($_POST["password"]);
        $user_id = rand(1000000000, 9999999999);
        $dialog_id = $_POST["dialog_id"];
        $admin_id = $_POST["admin_id"];


        function create_user () {
            global $name, $email, $password, $connect, $user_id, $admin_id, $dialog_id;
            if (strlen($name) > 0 & strlen($email) > 0 & strlen($password) > 0) {
                $connect->query("INSERT `users` (`name`,`email`,`password`,`user_id`) VALUES ('$name','$email','$password','$user_id')");
        
                setcookie("user_id", $user_id, time() + (86400 * 30) * 12, "/");
        
            } else {
                setcookie("invalid-data","Проверьте длину введеных данных",time() + 10,'/');
            }
        }

        $dialog_password = $connect->query("SELECT * FROM `dialogs` WHERE `dialog_id` = '$dialog_id'")->fetch_assoc()["dialog_password"];
        echo $dialog_password;
        if ($dialog_password != "") {
            $writed_dialog_password = $_POST["dialog_password"];
            if ($writed_dialog_password == $dialog_password) {
                create_user ();
            } else {
                setcookie("invalid-data","Неверный пароль",time() + 10,'/');
            }
        } else {
            create_user ();  
        }
        

    }
}

header("Location: ../../chat.php?admin_id=$admin_id&dialog_id=$dialog_id");
$connect->close();
