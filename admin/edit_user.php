<?php $page_title = "Admin Panel | Edit User";
require_once("../html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkAdminLoggedIn();
require_once(CLASSES_PATH . "AdminDataManager.php");

require_once("admin_includes/admin_nav.php"); ?>

    <div id="content">
        <div id="main-content">
            <?php
            $user_id = $_GET["user_id"];
            $u = getFullUserById($user_id);
            $type = getUserTypeById($user_id);
            $gender = $u['gender']; ?>
            <h4>Edit User</h4>
            <form action="edit_user?user_id=<?= $user_id ?>" class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3">Account Type: </label>
                    <div class="col-sm-4">
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="account_type" value="Tutor" <?php if ($type === "Tutor") echo "checked='checked'" ?> /> Tutor
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="account_type" value="Student" <?php if ($type === "Student") echo "checked='checked'" ?> /> Student
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">First Name: </label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                            <input name="first_name" value="<?= $u['first_name'] ?>" type="text" class="form-control" id="first-name" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Last Name: </label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                            <input name="last_name" value="<?= $u['last_name'] ?>" type="text" class="form-control" id="last-name" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Email: </label>
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input name="password" type="password" placeholder="New Password" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Confirm Password:</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                            <input name="confirm_password" type="password" class="form-control" placeholder="Confirm New Password"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Phone Number: </label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
                            <input name="phone_number" type="text" value="<?= $u['phone_number'] ?>" class="form-control" id="phone-number" placeholder="Phone Number" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Address: </label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></div>
                            <input name="address" type="text" value="<?= $u['address'] ?>" class="form-control" id="address" placeholder="Address">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Gender: </label>
                    <div class="col-sm-4">
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="Male" <?php if ($gender === "Male") echo "checked='checked'" ?> /> Male
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="Female" <?php if ($gender === "Female") echo "checked='checked'" ?> /> Female
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-3">
                        <input class="btn btn-lg btn-block btn-primary" type="submit" value="Save Changes"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php require_once("../html/html_footer.html"); ?>
