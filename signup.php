<?php
$page_title = "Sign Up for TutorMe";
require_once("html/html_header.html"); ?>

    <div class="my-nav"></div>

    <div id="content">
        <div id="main-content">
            <div class="col-sm-6">
                <?php
                require_once(LIBS_PATH . "recaptcha/ReCaptchaAutoload.php");
                require_once(CLASSES_PATH . "InfoManager.php");
                if (isset($_POST["first_name"], $_POST["last_name"], $_POST["password"], $_POST["confirm_password"], $_POST["email"], $_POST["phone_number"], $_POST["gender"], $_POST["account_type"])) {
                    $recaptcha = new \ReCaptcha\ReCaptcha($RECAPTCHA_SECRET_KEY);
                    if (isset($_POST["g-recaptcha-response"])) {
                        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                        if ($resp->isSuccess()) {
                            $signup = signUp($_POST);
                            if ($signup === true) {
                                echo "<script>BootstrapDialog.alert('" . ACCOUNT_CREATION_SUCCESS . "\n" . VERIFICATION_EMAIL_SUCCESS . "');</script>";
                            } else {
                                echo $signup;
                            }
                        } else {
                            echo "An error occured with the ReCaptcha processing...";
                        }
                    }
                }
                ?>
            </div>
            <br/>
            <br/>
            <form action="signup" method="post" enctype="multipart/form-data" class="form-horizontal" onsubmit="return checkValidSignupForm()">
                <h3 class="col-sm-offset-1">Create New Account</h3>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="input-first-name">First Name *:</label>
                        <input name="first_name" id="input-first-name" onkeyup="checkName()" onblur="checkName()" type="text" class="form-control" placeholder="First Name" required="required"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="input-last-name">Last Name *:</label>
                        <input name="last_name" id="input-last-name" onkeyup="checkName()" onblur="checkName()" type="text" class="form-control" placeholder="Last Name" required="required"/>
                    </div>
                    <span id="input-name-invalid" class="col-sm-6 vcenter text-nowrap invalid"></span>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="input-email">Email *:</label>
                        <input name="email" onkeyup="checkEmail()" onblur="checkEmail()" type="email" class="form-control" id="input-email" placeholder="Email Address" required="required"/>
                    </div>
                    <span id="input-email-invalid" class="col-sm-6 vcenter invalid"></span>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="input-phone-number">Phone Number *:</label>
                        <input name="phone_number" onblur="checkNumber()" id="input-phone-number" type="text" onkeyup="checkNumber()" class="form-control" placeholder="Phone Number" required="required"/>
                    </div>
                    <span id="input-phone-number-invalid" class="col-sm-6 vcenter text-nowrap invalid"></span>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="input-password">Password *:</label>
                        <input name="password" onkeyup="checkPassword()" onblur="checkPassword()" type="password" class="form-control" id="input-password" placeholder="Password" required="required"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="input-confirm-password" class="text-nowrap">Confirm Password *:</label>
                        <input name="confirm_password" id="input-confirm-password" onkeyup="checkConfirmPassword()" onblur="checkConfirmPassword()" type="password" class="form-control" placeholder="Confirm Password"
                               required="required"/>
                    </div>
                    <span id="input-password-invalid" class="col-sm-6 vcenter text-nowrap invalid"></span>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="input-address">Address:</label>
                        <input name="address" id="input-address" type="text" class="form-control" placeholder="Address"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="input-gender">Gender *:</label>
                        <div id="input-gender" class="row">
                            <div class="col-sm-6">
                                <label class="radio-inline">
                                    <input name="gender" id="input-gender-male" value="Male" type="radio"/>Male
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="radio-inline">
                                    <input name="gender" id="input-gender-female" value="Female" type="radio"/>Female
                                </label>
                            </div>
                        </div>
                    </div>
                    <span id="input-gender-invalid" class="col-sm-6 vcenter invalid"></span>
                </div>
                <!-- TODO: change this; `label` should be not be used for hidden elements! -->
                <div class="row">
                    <div class="col-sm-6">
                        <label for="input-account-type">Account Type *:</label>
                        <div id="input-account-type" class="row">
                            <div class="col-sm-6">
                                <label class="radio-inline">
                                    <input name="account_type" id="input-account-type-student" value="Student" type="radio"/>Student
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="radio-inline">
                                    <input name="account_type" id="input-account-type-tutor" value="Tutor" type="radio"/>Tutor
                                </label>
                            </div>
                        </div>
                    </div>
                    <span id="input-account-type-invalid" class="col-sm-6 vcenter invalid"></span>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label for="input-password">Profile Picture:</label>
                        <span class="btn btn-default btn-file btn-block" style="display: inline-block">
                            Browse <input name="profile_pic" id="input-profile-pic" type="file"/>
                        </span><span class="input-filename">No file chosen</span>
                    </div>
                </div>
                <br/>
                <div class="g-recaptcha" data-sitekey="<?= $RECAPTCHA_SITE_KEY ?>"></div>
                <script src="https://www.google.com/recaptcha/api.js?hl=<?= $LANG ?>"></script>
                <div class="form-group">
                    <div class="col-sm-6">
                        <input class="btn btn-lg btn-success btn-block" name="signup-submit" type="submit" value="Sign Up"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
