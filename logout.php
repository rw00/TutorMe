<?php
session_start();
unset($_SESSION["user_id"]);
session_regenerate_id(true);
session_destroy();
header("Location: index");
?>