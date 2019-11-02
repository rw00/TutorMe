<?php
$page_title = "Send Message";
require_once("html/html_min_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkLoggedIn();
if (!isset($_GET["id"])) {
    redirectIn();
}
if (isset($_GET["id"], $_POST["msg_text"])) {
    $other_id = $_GET["id"];
    $msg_text = trim($_POST["msg_text"]);
    if ($msg_text !== "") {
        require_once("php/classes/Messaging.php");
        sendMessage($_SESSION["user_id"], $other_id, $msg_text);
    }
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
                <form action="send_msg?id=<?= $_GET['id'] ?>" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label for="recipient" class="control-label">Recipient:</label>
                        <input id="recipient" name="other_id" class="form-control"/>
                        <input type="hidden" value="<?= $_GET['id'] ?>" name="id"/>
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="msg" class="control-label">Message:</label>
                        <textarea id="msg" name="msg_text" rows="10" class="form-control" required="required"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
