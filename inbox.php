<?php
$page_title = "Inbox";
require_once("html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkLoggedIn();
$user_id = $_SESSION["user_id"];
if (isset($_POST["msg_id"])) {
    deleteMsg($user_id, $_POST["msg_id"]);
    header("Location: inbox");
}
?>
    <div id="nav" class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index"><span class="title">TutorMe</span></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="home">Home</a></li>
                    <li><a href="profile">Profile</a></li>
                    <li><a href="logout">Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id="content">
        <div id="main-content">
            <div class="panel panel-default">
                <div class="panel-heading">Your Inbox Messages</div>
                <div class="panel-body">
                    <div class="list-group" data-ng-init='msgs=<?= json_encode(getInboxMsgs($user_id)) ?>'>
                        <div data-ng-repeat='msg in msgs'>
                            <msg-info info='msg'></msg-info>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
