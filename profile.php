<?php
$page_title = "My Profile";
require_once("html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkLoggedIn();
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
                    <li><a href="home">Home</a></li>
                    <li class="active"><a href="profile">Profile</a></li>
                    <li><a href="logout">Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id="content">
        <div id="main-content">
            <?php
            $user_id = $_SESSION["user_id"];
            if (isset($_POST["current_password"])) {
                if (isset($_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["current_password"], $_POST["phone_number"], $_POST["address"], $_POST["password"], $_POST["confirm_password"])) {
                    $update = updateProfile($user_id, $_POST);
                    $update === true ? $update = UPDATE_PROFILE_SUCCESS : $update = $update;
                    # $update = ($update === true) ? UPDATE_PROFILE_SUCCESS : $update;
                    $_SESSION["notice"] = $update;
                    displayNotice();
                }
            }
            $u = getFullUserById($user_id);
            $type = getUserTypeById($user_id);
            ?>
            <h4> Profile Information </h4>
            <form action="profile" id="profile-form" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3">Account Type: </label>
                    <div class="col-sm-4" style="margin-top: 9px;">
                        <?php if ($type === "Student") {
                            echo "Student";
                        } else if ($type === "Tutor") {
                            echo "Tutor";
                        } else {
                            redirectOut();
                        } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">First Name: </label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                            <input name="first_name" value="<?= $u['first_name'] ?>" type="text" class="form-control" id="first-name" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Last Name: </label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                            <input name="last_name" value="<?= $u['last_name'] ?>" type="text" class="form-control" id="last-name" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Email: </label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <!--<span class="glyphicon glyphicon-envelope"></span>-->@
                            </div>
                            <input name="email" value="<?= $u['email'] ?>" type="email" class="form-control" id="email" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">New Password:</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input name="password" type="password" placeholder="New Password" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Confirm Password:</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input name="confirm_password" type="password" class="form-control" placeholder="Confirm New Password"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Phone Number: </label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
                            <input name="phone_number" type="text" value="<?= $u['phone_number'] ?>" class="form-control" id="phone-number" placeholder="Phone Number" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Address: </label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></div>
                            <input name="address" type="text" value="<?= $u['address'] ?>" class="form-control" id="address" placeholder="Address">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Gender: </label>
                    <div class="col-sm-4" style="margin-top: 9px;">
                        <?php if ($u["gender"] === "Male") {
                            echo "Male";
                        } else {
                            echo "Female";
                        } ?>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label class="control-label col-sm-3">Current Password: </label>
                    <div class="col-sm-3">
                        <input id="current-password" name="current_password" type="password" class="form-control" placeholder="Current Password" required="required"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-3">
                        <input id="update-profile" class="btn btn-lg btn-block btn-primary" type="submit" value="Save Changes"/>
                    </div>
                </div>
            </form>

            <br/>
            <h4> Delete Account </h4>
            <form action="profile" id="delete-account-form" class="form-horizontal" onsubmit="confirmDeleteAccount(); return false;" method="post">
                <p class='text-danger'> Deleting your account will remove all your information from our system! It is an irreversible action. </p>
                <div class="form-group">
                    <div class="col-sm-4">
                        <input id="password-field-delete-account" type="password" class="form-control" name="current_password_del" placeholder="Current Password" required="required"/>
                        <input id="submit-delete" type="submit" class="btn btn-block" value="Delete Account"/>
                    </div>
                </div>
            </form>
            <p class="text-danger">
                <?php
                if (isset($_POST["current_password_del"])) {
                    $current_password = trim($_POST["current_password_del"]);
                    $del = deleteAccount($user_id, $current_password);
                    if ($del !== true) {
                        $_SESSION["notice"] = $del;
                        displayNotice('error');
                    } else {
                        header("Location: logout");
                    }
                }
                ?>
            </p>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
