<?php
$page_title = "Welcome to TutorMe";
require_once("html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkLoggedIn();
$user_id = $_SESSION["user_id"];
?>
    <div id="nav" class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand"><span class="title">TutorMe</span></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="home">Home</a></li>
                    <li><a href="profile">Profile</a></li>
                    <li><a href="inbox">Inbox <span class="badge"><?= getNumberOfUnreadMsgs($user_id) ?></span></a></li>
                    <li><a href="outbox">Outbox</a></li>
                    <li><a href="logout">Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id="content">
        <div id="main-content">
            <?php displayFlashMsg(); ?>

            <br/>
            <form method="get" action="home" class="form-horizontal">
                <div class="col-sm-4">
                    <input name="search" id="search-input" type="text" placeholder="Search..." class="form-control"/>
                </div>
                <div class="col-sm-2">
                    <input type="submit" value="Search" class="btn btn-default btn-block"/>
                </div>
            </form>
            <br/>
            <br/>
            <div id="search-result" <?php if (isset($_GET['search'])) {
                echo "data-ng-init='courses=" . jsearchCourses($_GET['search']) . "'";
            } ?>>
                <?php
                if (isset($_GET['search'])) {
                    ?>
                    <div class="col-sm-6" data-ng-repeat='course in courses'>
                        <course-info info="course"></course-info>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
