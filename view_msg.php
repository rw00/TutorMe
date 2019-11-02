<?php
$page_title = "View Message";
require_once("html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkLoggedIn();
if (!isset($_GET["msg_id"])) {
    redirectIn();
}
$user_id = $_SESSION["user_id"];
$msg_id = $_GET["msg_id"];
if (isMyInboxMsg($user_id, $msg_id)) {
    setReadMsg($msg_id);
} else if (!isMySentMsg($user_id, $msg_id)) {
    redirectIn();
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
            <div class="centered">
                <div class="form-group">
                    <label for="recipient" class="control-label">Recipient:</label>
                    <input id="recipient" class="form-control" readonly="readonly"/>
                </div>
                <br/>
                <div class="form-group">
                    <label for="msg" class="control-label">Message:</label>
                    <textarea id="msg" class="form-control" rows="10" required="required" readonly="readonly"><?= getMsgText($msg_id) ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
