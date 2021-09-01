    <?php 
    $title = "anchat";
    require("blocks/header.php");
    ?>
    
    <?php if ($_COOKIE["user_id"] == "") : ?>
        <div class="body__login-container">
            <form action="php/validation/auth.php" method="POST">
                <p class="login__title">Войти в свой аккаунт</p>
                <p class="login__title-error"><?=$_COOKIE["invalid-length-auth"]?></p>
                <input class="login__input" name="email" placeholder="Email" type="email">
                <input class="login__input" name="password" placeholder="Пароль" type="password">
                <button class="login__btn" id="login">Войти</button>
            </form>
            <form action="php/validation/create_new_account.php" method="POST">
                <p class="login__title">Создать новый аккаунт</p>
                <p class="login__title-error"><?=$_COOKIE["invalid-length-login"]?></p>
                <input class="login__input" name="name" placeholder="Никнейм" type="text">
                <input class="login__input" name="email" placeholder="Email" type="email">
                <input class="login__input" name="password" placeholder="Пароль" type="password">
                <button class="login__btn" id="create-account">Создать</button>
            </form>
        </div>
    <?php else : ?>
        <div class="body__create-room-container">
            <form class="create-room-container__form" action="php/create_room/create_room.php" method="POST">
                <input type="hidden" name="user_id" value="<?= $_COOKIE["user_id"] ?>">
                <button class="create-room-container__button">Создать комнату</button>
            </form>
        </div>

        <div class="body__list-of-room">
            <h2 class="list-of-room__title">Все комнаты в которых вы состоите:</h2>
            <div class="list-of-room__container">

                <?php
                require("php/connect.php");
                $user_id = $_COOKIE["user_id"];

                $dialogs = $connect->query("SELECT * FROM `dialogs` WHERE `admin_id` = '$user_id' OR `friend_id` = '$user_id'")->fetch_all();

                for ($i = 0; $i < count($dialogs); $i++) {
                    if ($dialogs[$i][0] == $user_id) {
                        echo '  
                        <div class="container__link-to-room">
                            <a class="link-to-room__a" href="http://i89368.hostch01.fornex.org/chat.php?admin_id=' . $dialogs[$i][0] . '&dialog_id=' . $dialogs[$i][2] . '">http://i89368.hostch01.fornex.org/chat.php?admin_id=' . $dialogs[$i][0] . '&dialog_id=' . $dialogs[$i][2] . '</a>
                            Вы администратор этой комнаты
                        </div>
                            ';
                    } else {
                        echo '  
                        <div class="container__link-to-room">
                            <a class="link-to-room__a" href="http://i89368.hostch01.fornex.org/chat.php?admin_id=' . $dialogs[$i][0] . '&dialog_id=' . $dialogs[$i][2] . '">http://i89368.hostch01.fornex.org/chat.php?admin_id=' . $dialogs[$i][0] . '&dialog_id=' . $dialogs[$i][2] . '</a>
                            Вы не администратор этой комнаты
                        </div>
                        
                            ';  
                    }
                }

                ?>

            </div>
        </div>

    <?php endif ?>
    </body>

    </html>