<?php

require("../connect.php");

function sanitize_string($text)
{
    return htmlentities(filter_var(trim($text), FILTER_DEFAULT));
}

function can_upload ($file) {
    if ($file["name"] == '') {
       return "файл не загружен";
    } 
    if ($file["size" == 0]) {
        return "файл слишком большой";
    }
    $get_mime = explode('.',$file["name"]);
    $mime = strtolower(end($get_mime));
    $types = array("jpg",'jped','png');

    if (!in_array($mime,$types)) {
       return "Недопустимый тип файла";
    }
    return true;
}
function make_upload ($file,$connect,$text,$sender_id,$dialog_id,$message_id) {
    $get_mime = explode('.',$file["name"]);
    $mime = strtolower(end($get_mime));
    $name = random_int(10000000000,99999999999).'.'.$mime;
    if (strlen($mime) > 0) {
        copy($file["tmp_name"],"../../img/$name");
        $connect->query("INSERT `messages` (`text`,`image`,`sender_id`,`dialog_id`,`message_id`) VALUES ('$text','$name','$sender_id','$dialog_id','$message_id')");    
    }
}

if ($connect->connect_errno) {
    echo "Ошибка" . $connect->connect_error;
} else {
    $dialog_id = $_POST["dialog_id"];
    $sender_id = $_COOKIE["user_id"];
    $admin_id = $_POST["admin_id"];
    $text = sanitize_string($_POST["text"]);
    $message_id = rand(10000000000, 99999999999);

    if (isset($_FILES["image"])) {
        $check = can_upload($_FILES["image"]);

        if ($check) {
            make_upload($_FILES["image"],$connect,$text,$sender_id,$dialog_id,$message_id);
        } else {
            header("Location: ../../chat.php?admin_id=$admin_id&dialog_id=$dialog_id");
        }
     }

    if (strlen($text) > 0 & $check == true) {
        $connect->query("INSERT `messages` (`text`,`sender_id`,`dialog_id`,`message_id`) VALUES ('$text','$sender_id','$dialog_id','$message_id')");

    } else {
        header("Location: ../../chat.php?admin_id=$admin_id&dialog_id=$dialog_id");
    }

    header("Location: ../../chat.php?admin_id=$admin_id&dialog_id=$dialog_id");
    $connect->close();
}
