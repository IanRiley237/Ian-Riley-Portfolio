<?php
    session_start();

    unset($_SESSION["Account_ID"]);
    unset($_SESSION["Is_Owner"]);
    unset($_SESSION["Is_Admin"]);
    unset($_SESSION["Is_Moderator"]);
    unset($_SESSION["Username"]);
    unset($_SESSION["Email"]);
    unset($_SESSION["Creation_Timestamp"]);

    session_destroy();
    header("Location: /gato");
?>