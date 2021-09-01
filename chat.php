<?php
require("php/connect.php");
$dialog_id  = $_GET["dialog_id"];

$dialog_title = $connect->query("SELECT `dialog_title` FROM `dialogs` WHERE `dialog_id` = '$dialog_id'")->fetch_assoc()["dialog_title"];

$user_id = $_COOKIE["user_id"];
$admin_id = $_GET["admin_id"];

$my_name = $connect->query("SELECT * FROM `users` WHERE `user_id` = '$admin_id'")->fetch_assoc()["name"];

if ($dialog_title != '') {
    $title = $dialog_title;
} else {
    $title = "Комната $my_name";
}
require("blocks/header.php");
$connect->close();

?>
<?php if ($_COOKIE["user_id"] == "") : ?>
    <div class="body__login-container">
        <form action="php/create_room/enter_the_room.php" method="POST">
            <p class="login__title">Чтобы войти в комнату необходимо зарегистрироваться</p>
            <p class="login__title-error"><?= $_COOKIE["invalid-data"] ?></p>
            <input value="<?= $_GET["dialog_id"] ?>" name="dialog_id" type="hidden">
            <input value="<?= $_GET["admin_id"] ?>" name="admin_id" type="hidden">
            <input class="login__input" name="name" placeholder="Никнейм" type="text">
            <input class="login__input" name="email" placeholder="Email" type="email">
            <input class="login__input" name="password" placeholder="Пароль от аккаунта" type="password">
            <?php
            require("php/connect.php");
            $dialog_id  = $_GET["dialog_id"];
            $dialog_password = $connect->query("SELECT * FROM `dialogs` WHERE `dialog_id` = '$dialog_id'")->fetch_assoc()["dialog_password"];
            $connect->close();
            ?>
            <?php if ($dialog_password != "") : ?>
                <input class="login__input" name="dialog_password" placeholder="Пароль от комнаты" type="password">
            <?php endif ?>
            <button class="login__btn" id="create-account">Войти в комнату</button>
        </form>
    </div>
<?php else : ?>
    <?php
    require("php/connect.php");


    if ($admin_id != $user_id) {
        $connect->query("UPDATE `dialogs` SET `friend_id` = '$user_id'");
    }

    $connect->close();

    ?>
    <div class="body__messenger-container">
        <header class="messenger-container__header">
            <?php
            require("php/connect.php");

            if ($connect->connect_errno) {
                echo "Ошибка" . $connect->connect_error;
            } else {

                if ($admin_id == $_COOKIE["user_id"]) {
                    $user = $connect->query("SELECT * FROM `dialogs` WHERE `admin_id` = '$admin_id' AND `dialog_id` = '$dialog_id'")->fetch_assoc()["friend_id"];
                } else {
                    $user = $connect->query("SELECT * FROM `dialogs` WHERE `dialog_id` = '$dialog_id'")->fetch_assoc()["admin_id"];
                }

                $name_of_friend = $connect->query("SELECT * FROM `users` WHERE `user_id` = '$user'")->fetch_assoc()["name"];
                echo $name_of_friend;
            }
            $connect->close();
            ?>

        </header>
        <div class="messenger-container__dialog">

            <?php
            require("php/connect.php");

            $all_messages = $connect->query("SELECT *FROM `messages` WHERE `dialog_id` = '$dialog_id' ORDER BY `date` ASC")->fetch_all();

            for ($i = 0; $i < count($all_messages); $i++) {
                if ($all_messages[$i][2] == $user_id) {
                    $text = $all_messages[$i][0];
                    echo "
                <div class='dialog__message dialog__my-message'>
                    <p class='message__name'>$my_name</p>
                    <xmp class='message__text'>$text</xmp>";
                    if ($all_messages[$i][1] != null) {
                        echo '<img class="message__image" src="img/' . $all_messages[$i][1] . '" alt="">';
                    }
                    echo "</div>";
                } else {
                    $text = $all_messages[$i][0];
                    echo "
                <div class='dialog__message dialog__friend-message'>
                    <p class='message__name'>$name_of_friend</p>
                    <xmp class='message__text'>$text</xmp>";
                    if ($all_messages[$i][1] != null) {
                        echo '<img class="message__image" src="img/' . $all_messages[$i][1] . '" alt="">';
                    }
                    echo "</div>";
                }
            }
            $connect->close();

            ?>
        </div>
        <footer class="messenger-container__footer">
            <form id="ajax_form" action="php/send_message/send_message.php" method="POST" enctype="multipart/form-data">
                <input value="<?= $user ?>" name="friend_id" type="hidden">
                <input value="<?= $dialog_id ?>" name="dialog_id" type="hidden">
                <input value="<?= $admin_id ?>" name="admin_id" type="hidden">
                <input value="<?= $_COOKIE["user_id"] ?>" name="my_id" type="hidden">

                <input class="footer__input-message" name="text" placeholder="Ваше сообщение" type="text">
                <input id="footer__input-file" name="image" class="footer__input-file" type="file">

                <label for="footer__input-file" class="footer__label-input-file">
                    <span class="label-input-file__span">Добавить изображение</span>
                </label>
                <button class="footer__btn-send"><i class="far fa-paper-plane"></i></button>
            </form>
        </footer>
    </div>

    <div class="body__link-to-room body__action-to-room">
        <p class="action-to-room__title">Ссылка на комнату</p>
        <div class="action-to-room__action-container">
            <input id="" type="text" class="action-container__input" disabled>
            <script>
                document.querySelector('.action-container__input').value = location.href
            </script>
            <button class="action-container__copy action-container__btn"><i class="far fa-copy"></i></button>
            <script>
                const btnCopy = document.querySelector('.action-container__copy')
                const input = document.querySelector('.action-container__input');
                btnCopy.onclick = () => {
                    input.disabled = false;
                    input.select();
                    input.disabled = true;
                    document.execCommand("copy")
                }
            </script>
        </div>
    </div>
    <div class="body__reload-room body__action-to-room">
        <p class="action-to-room__title">Обновить страницу</p>
        <div class="action-to-room__action-container">
            <button class="action-container__reload action-container__btn"><i class="fas fa-sync-alt"></i></button>
            <script>
                const btnReload = document.querySelector('.action-container__reload');
                btnReload.onclick = () => {
                    location.reload();
                }
            </script>
        </div>
    </div>
    <?php if ($admin_id == $user_id) : ?>
        <div class="body__make-password body__action-to-room">
            <p class="action-to-room__title">Задать пароль для комнаты</p>
            <div class="action-to-room__action-container">
                <form action="php/create_room/change_password.php" method="POST">
                    <input value="<?= $dialog_id ?>" name="dialog_id" type="hidden">
                    <input value="<?= $admin_id ?>" name="admin_id" type="hidden">
                    <input type="text" class="action-container__input" value="<?= $_COOKIE["small-length-password"] ?>" name="password" placeholder="Пароль">
                    <button class="action-container__make-password"><i class="fas fa-pen"></i></button>
                </form>
            </div>
            <?php
            require("php/connect.php");
            try {
                $dialog_password = $connect->query("SELECT `dialog_password` FROM `dialogs` WHERE `dialog_id` = '$dialog_id'")->fetch_assoc()["dialog_password"];
            } catch (\Throwable $th) {
                $dialog_password = "Сейчас комната не запаролена";
            }
            $connect->close();
            ?>
            <p class="action-to-room__title">Сейчас пароль у комнаты - <?= $dialog_password ?></p>
        </div>
        <div class="body__make-title body__action-to-room">
            <p class="action-to-room__title">Написать заголовок для комнаты</p>
            <div class="action-to-room__action-container">
                <form action="php/create_room/change_title.php" method="POST">
                    <input value="<?= $dialog_id ?>" name="dialog_id" type="hidden">
                    <input value="<?= $admin_id ?>" name="admin_id" type="hidden">
                    <input type="text" name="dialog_title" class="action-container__input" value="<?= $_COOKIE["small-length-title"] ?>" placeholder="Текст заголовка">
                    <button class="action-container__make-title action-container__btn">
                        <i class="fas fa-pen"></i>
                    </button>

                </form>
            </div>
        </div>
    <?php endif ?>

<?php endif ?>
</body>

</html>