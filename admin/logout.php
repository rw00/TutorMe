<?php
session_start();
unset($_SESSION["admin"]);
session_regenerate_id(true);
session_destroy();
header("Location: ../index");
?>