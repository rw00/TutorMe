<?php
require_once("php/classes/InfoManager.php");
if (isset($_GET["search"])) {
    $course = htmlspecialchars($_GET["search"]);
    echo jsearchCourses($course);
}
?>