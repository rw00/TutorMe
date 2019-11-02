<?php
$page_title = "TutorMe Reset Profile";
require_once("html/html_header.html"); ?>

    <div id="content">
        <div id="main-content">
            <h4 class="text-center"><span class="title">TutorMe</span> Account Activation</h4>
            <?php
            require_once(CLASSES_PATH . "InfoManager.php");

            $false_info = "<p> This account does not exist in our system! </p>" . $redirect_script;
            # requested password reset...
            if (isset($_POST["user_email"])) {
                $email = $_POST["user_email"];
                if (userExists($email) === true) {
                    $user_id = getUserIdByEmail($email);
                    sendResetMail($email, $user_id, updateCode($user_id));
                    echo "<p> An email with instructions to reset your password was sent to your inbox. </p>";
                } else {
                    echo $false_info;
                }
            } else if (isset($_GET["id"], $_GET["code"])) { # checking reset password link...
                $id = $_GET["id"];
                $code = $_GET["code"];
                $u = getFullUserById($id);
                $valid = true;
                if ($u === null) {
                    echo $false_info;
                    $valid = false;
                }
                if ($code !== $u["activation_code"]) {
                    echo $FALSE_INFO_ERR;
                } else if ($valid) {
                    require_once("views/change_password_form.html");
                }
            } else if (isset($_POST["password"], $_POST["confirm_password"], $_POST["id"])) { # resetting password...
                $id = $_POST["id"];
                $password = trim($_POST["password"]);
                $confirm_password = trim($_POST["confirm_password"]);
                $valid = true;
                if (!isValidPassword($password)) {
                    echo "<p>" . INVALID_PASSWORD_ERR . "</p>" . $back_script;
                    $valid = false;
                } else if ($password !== $confirm_password) {
                    echo "<p>" . INVALID_CONFIRM_PASS_ERR . "</p>" . $back_script;
                    $valid = false;
                }
                $u = getUserById($id);
                if (is_null($u)) { # TODO: && isValidCode($u["activation_code"]) # no?
                    echo $FALSE_INFO_ERR;
                } else if ($valid) {
                    changePassword($id, hashPassword($password));
                    activateAccount($id);
                    echo "<p> Password successfully changed! </p>" . $redirect_script;
                }
            } else {
                echo $redirect_script;
            }
            ?>
        </div>
    </div>

<?php require_once("html/html_footer.html"); ?>
