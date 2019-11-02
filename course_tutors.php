<!DOCTYPE html>
<html data-ng-app="TutorMeApp">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=0.75"/>
    <title>Course Tutors</title>
    <link rel="shortcut icon" href="img/favicon.ico"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/main.css"/>
</head>

<body>
<div id="content">
    <div id="main-content">
        <?php
        require_once("php/classes/InfoManager.php");
        if (!isLoggedIn() || !isset($_GET["subject_name"], $_GET["course_number"])) {
            redirectOut();
        }
        $course = strtoupper($_GET["subject_name"]) . " " . $_GET["course_number"];
        ?>
        <div class="list-group" data-ng-init='tutors=<?= jgetTutors($course) ?>'>
            <div data-ng-repeat='tutor in tutors'>
                <tutor-info info='tutor'></tutor-info>
            </div>
        </div>
    </div>
</div>

<script src="js/vendors/angular.min.js"></script>

<!-- Modules -->
<script src="js/app.js"></script>

<!-- Directives -->
<script src="js/directives/myFooter.js"></script>
<script src="js/directives/tutorInfo.js"></script>
</body>

</html>
