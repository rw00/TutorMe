<?php
$page_title = "Welcome to TutorMe";
require_once("html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
if (isLoggedIn()) {
    redirectIn();
}

if (isset($_POST["activate"])) {
    if (isset($_POST["user_id"])) {
        $user_id = $_POST["user_id"];
        if (userExistsById($user_id) === true) {
            updateCode($user_id);
            if (accountActivation($user_id) === true) {
                $_SESSION["flash_msg"] = ACTIVATION_EMAIL_SUCCESS;
            } else {
                $_SESSION["flash_msg"] = ACTIVATION_EMAIL_FAIL;
            }
        }
    }
}
?>

    <div class="my-nav"></div>

    <div id="content">
        <div id="main-content">
            <div class="centered">
                <?php displayFlashMsg(); ?>

                <?php
                if (isset($_POST["email"], $_POST["password"])) {
                    $login = getUserId($_POST["email"], $_POST["password"]);
                    if (isNum($login)) {
                        $_SESSION["user_id"] = $login;
                        redirect("home", WELCOME_BACK);
                    } else {
                        echo $login;
                    }
                }
                ?>

                <br/> <?php # for return_url purposes ?>
                <form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>" class="form-signin">
                    <h3 class="form-signin-heading">Log in</h3>
                    <label for="input-email" class="sr-only">Email address</label>
                    <input name="email" value="<?= getRememberMeEmail() ?>" id="input-email" type="email" class="form-control" placeholder="Email address" required="required" autofocus="autofocus"/>
                    <label for="input-password" class="sr-only">Password</label>
                    <input name="password" id="input-password" type="password" class="form-control" placeholder="Password" required="required"/>
                    <label>
                        <a id="forgot-password" href="#">Forgot Password?</a></label>
                    <br>
                    <div class="checkbox">
                        <label for="remember-me">
                            <input id="remember-me" name="remember_me" value="remember_me" type="checkbox" <?= checkRemembered() ?> /> Remember Me
                        </label>
                    </div>
                    <input id="login-submit" type="submit" value="Sign in" class="btn btn-lg btn-primary btn-block"/>
                    <input id="login-submit-disabled" type="submit" value="Sign in" class="btn btn-lg btn-default btn-block" disabled="disabled"/>
                </form>
                <br/>
                <br/>
            </div>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
