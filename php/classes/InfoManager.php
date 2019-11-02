<?php
require_once(realpath(__DIR__) . "/../../config.php");
require_once(CLASSES_PATH . "Helper.php");

define("INVALID_LOGIN_ERR", " Invalid login. Please enter your email and password correctly. ");
define("INACTIVE_ACCOUNT_ERR", " Your account isn't activated yet! ");

define("INVALID_EMAIL_ERR", " Invalid email address! Please enter your correct email address. ");
define("INVALID_NAME_ERR", " Invalid name! Please enter your real name in English letters only. ");
define("INVALID_PHONE_NUM_ERR", " Invalid phone number! Please enter your correct phone number. ");
define("INVALID_PASSWORD_ERR", " Invalid password! Please enter a password of at least " . MIN_PASS_LEN . " characters long without any spaces. ");
define("INVALID_CONFIRM_PASS_ERR", " Invalid confirm password. The input password and confirm password don't match. ");
define("INVALID_CURRENT_PASS_ERR", " Invalid current password. Please enter your password correctly to apply changes. ");
define("INVALID_GENDER_ERR", " Invalid gender! Please select either Male or Female! ");

define("INVALID_ACCOUNT_TYPE_ERR", " Invalid account type! Please select either Student or Tutor! ");

define("ACCOUNT_ALREADY_EXISTS_ERR", " This email already exists in our database. ");
define("ACCOUNT_NOT_FOUND_ERR", " This email doesn't exist in our database. Click <a href='signup'>here to sign up</a> now for free! ");

define("INVALID_INFO_ERR", " You submitted invalid information! ");
define("UNKNOWN_ERR", " An unknown error occurred. ");

define("INVALID_IMG_ERR", " Invalid image! Please correctly upload a valid image file. Sorry, but we only support gif, jpg and png images! ");

define("UPDATE_FAIL_ERR", " Could not update information! ");
define("UPDATE_FAIL_UNKNOWN_ERR", UNKNOWN_ERR . UPDATE_FAIL_ERR);

define("RETRY_MSG", " Please try again later. ");

define("INVALID_CONTACT_SUBJECT_ERR", " Invalid subject. Please select a valid reason/subject to contact us. ");
define("INVALID_CONTACT_COMMENTS_ERR", " Invalid comment. Please enter something meaningful. ");

define("EMAIL_FAIL_ERR", " Could not send email. ");


define("UPDATE_PROFILE_FAIL", " Could not update your profile information! ");
define("ACTIVATION_FAIL", " Could not activate your account! ");
define("ACCOUNT_CREATION_FAIL", " Could not create an account for you! ");
define("CONTACT_US_FAIL", " Could not send this contact us email. ");
define("ACTIVATION_EMAIL_FAIL", " Verification email could not be sent. " . RETRY_MSG);

define("UPDATE_PROFILE_SUCCESS", " Successfully updated your profile information! ");
define("ACTIVATION_SUCCESS", " Successfully activated your account! ");
define("ACCOUNT_CREATION_SUCCESS", " Successfully created an account for you! ");
define("CONTACT_US_SUCCESS", " Successfully sent your comments. Thank you! ");
define("ACTIVATION_EMAIL_SUCCESS", " Check your inbox for an activation email from us. ");

require_once(CLASSES_PATH . "DBManager.php");
require_once(CLASSES_PATH . "UserManager.php");
require_once(CLASSES_PATH . "Mailing.php");
require_once(CLASSES_PATH . "Messaging.php");
require_once(CLASSES_PATH . "FlashMsgConstants.php");

define("DEFAULT_IMG_EXTENSION", ".png");
define("DEFAULT_PROFILE_PIC", "/img/user" . DEFAULT_IMG_EXTENSION);
define("PROFILE_PIC_PATH", "user_data/profile_pic/");
define("TEMP_DIR_PATH", "temp_data/");

$TIMER = 5; # in seconds
$redirect_script =
    "<script>
var t = {$TIMER};
var timer = parseInt(t + \"000\");

document.writeln(\" You will be redirected to home page in <span  id='countdown'>\" + t + \"</span> <span id='countdown-seconds-text'>second(s)</span>... \");

function redirecting() {
    window.location = 'index';
}
setTimeout(redirecting, timer);

function countDown() {
    var counter = document.getElementById('countdown');
    counter.innerHTML = (counter.innerHTML * 1) - 1;
}
setInterval(countDown, 1000);
</script>";

$back_script =
    "<script>
var t = {$TIMER};
var timer = parseInt(t + \"000\");
document.writeln(\" Returning in \" + t + \" second(s)... \");
setTimeout(function() { window.history.back(); }, timer);
</script>";

$FALSE_INFO_ERR = "<p>" . INVALID_INFO_ERR . "</p>" . $redirect_script;

# ReCaptcha settings:
# would run on localhost only /:
$LANG = "en";
$RECAPTCHA_SITE_KEY = "6LeVzQ0TAAAAANxzLBi8gz03cDqRWIUV3kfngvmc";
$RECAPTCHA_SECRET_KEY = '6LeVzQ0TAAAAAPkoFfUQacy9y6asE9kFvDiCnRUa';

$CONTACT_US_SUBJECTS = array("Job Application", "Recommendation", "Bug Report");

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Returns today's date string representation in the form yyyy-mm-dd
 * @return string String representation of today's date in format of yyyy-mm-dd
 * @author Raafat
 */
function getToday()
{
    return date("Y-m-d");
}

# for login
function getUserId($email, $password)
{
    $email = fixEmail($email);
    $password = trim($password);
    if (checkAdmin($email, $password) === true) {
        $_SESSION["admin"] = $email;
        header("Location: admin/");
        exit;
    }

    $u = getFullUser($email, $password); # full user information
    if ($u === null) { # wrong password or account does not exist
        return INVALID_LOGIN_ERR;
    } elseif ($u["activation_code"] !== "0") { # account not verified
        return INACTIVE_ACCOUNT_ERR .
            " Please check your inbox for an email from us. Or click the button below to request another verification link. <br />
        <form action='index' method='post' class='form-horizontal'>
            <input type='hidden' name='user_id' value='{$u["user_id"]}' />
            <div style='text-align: center'>
                <button name='activate' class='btn btn-default' type='submit'>Submit</button>
            </div>
        </form>";
    } else {
        checkLoginRememberMe($email);
        return $u["user_id"];
    }
}

function signUp($user)
{
    $user = validateFixProfile($user);
    if (is_string($user)) { # error msg: invalid info
        return $user;
    }
    if (userExists($user["email"])) {
        return ACCOUNT_ALREADY_EXISTS_ERR;
    }
    $user["password"] = trim($user["password"]);
    $checkPassword = checkPassword($user["password"], $user["confirm_password"]);
    if (is_string($checkPassword)) {
        return $checkPassword;
    }
    $account_type = $user["account_type"];
    if ($account_type !== "Tutor" && $account_type !== "Student") {
        return INVALID_ACCOUNT_TYPE_ERR;
    }
    $gender = $user["gender"];
    if ($gender !== "Male" && $gender !== "Female") {
        return INVALID_GENDER_ERR;
    }
    if (is_uploaded_file($_FILES["profile_pic"]["tmp_name"]) && isValidImg("profile_pic") !== true) {
        return INVALID_IMG_ERR;
    }
    $user_id = insertUser($user);
    if (isNum($user_id)) {
        insertInto($account_type, $user_id);
        if (file_exists($_FILES["profile_pic"]["tmp_name"])) {
            $path = getProfilePicPath($user_id);
            moveFile("profile_pic", getTempPath($user_id), $path);
            changeProfilePic($user_id, $path);
        }
        # else {
        #    changeProfilePic($user_id, DEFAULT_PROFILE_PIC);
        # }
        $u = getFullUserById($user_id);
        if (sendActivationMail($u["email"], $user_id, $u["activation_code"])) {
            return true;
        } else {
            return " Account successfully created but could not send you a verification email. Please request another one. ";
        }
    } else {
        return UNKNOWN_ERR . RETRY_MSG;
    }
}

function updateProfile($user_id, $user)
{
    $user = validateFixProfile($user);
    if (is_string($user)) { # error msg
        return $user;
    }
    if (checkPasswordById($user_id, $user["current_password"])) {
        return INVALID_CURRENT_PASS_ERR;
    }
    if ($user["password"] !== "") {
        $checkPasswords = checkPasswords($user["password"], $user["confirm_password"]);
        if (is_string($checkPasswords)) {
            return $checkPasswords;
        }
    } else {
        $user["password"] = $user["current_password"];
    }
    $newEmail = false;
    if (getFullUserById($user_id)["email"] !== $user["email"]) { # user changed email
        if (userExists($user["email"]) === true) {
            return ACCOUNT_ALREADY_EXISTS_ERR . UPDATE_FAIL_ERR;
        }
        $newEmail = true;
    }
    if (updateUser($user_id, $user) === true) {
        if ($newEmail === true) {
            $code = updateCode($user_id);
            if (is_string($code)) {
                sendActivationMail($user["email"], $user_id, $code);
            }
        }
        return true;
    } else {
        return false;
    }
}

function validateFixProfile($user)
{ # password, confirm_password, current_password
    extract($user, EXTR_PREFIX_SAME, "r");
    $email = fixEmail($email);
    if (!isValidEmail($email)) {
        return INVALID_EMAIL_ERR;
    }
    $phone_number = trim($phone_number);
    if (!isValidPhoneNumber($phone_number)) {
        return INVALID_PHONE_NUM_ERR;
    }
    $first_name = fixName($first_name);
    $last_name = fixName($last_name);
    if (!isValidName($first_name) || !isValidName($last_name)) {
        return INVALID_NAME_ERR;
    }

    $user["email"] = $email;
    $user["first_name"] = $first_name;
    $user["last_name"] = $last_name;
    $user["phone_number"] = $phone_number;

    $user["password"] = trim($user["password"]);
    $user["confirm_password"] = trim($user["confirm_password"]);
    if (isset($user["current_password"])) {
        $user["current_password"] = trim($user["current_password"]);
    }
    return $user;
}

function deleteAccount($user_id, $password)
{
    $password = trim($password);
    if (checkPasswordById($user_id, $password) === true) {
        if (deleteUser($user_id) === true) {
            return true;
        } else {
            return UNKNOWN_ERR;
        }
    } else {
        return INVALID_CURRENT_PASS_ERR;
    }
}

function checkPasswordById($user_id, $password)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `password` FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $user_password = $stmt->execute();
    if ($stmt->fetch()["password"] === trim($password)) {
        return true;
    } else {
        return false;
    }
}

function checkPasswords($password, $confirm_password)
{
    $password = trim($password);
    if (!isValidPassword($password)) {
        return INVALID_PASSWORD_ERR;
    }

    $confirm_password = trim($confirm_password);
    if ($confirm_password !== $password) {
        return INVALID_CONFIRM_PASS_ERR;
    }
    return true;
}

/**
 * Generates a new activation code for the user and sets it directly.
 * @param integer $user_id The unique ID of the user to update their activation_code
 * @return mixed  False if the code was not updated. Otherwise, return the code.
 * @author Raafat
 */
function updateCode($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user` SET `activation_code` = :activation_code WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $code = getCode();
    $stmt->bindValue(":activation_code", $code);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $code;
    } else {
        return false;
    }
}

/**
 * Sends the activation email for the user and updates the activation_code.
 * @param integer $user_id The unique ID of the user.
 * @return boolean True if the email was sent and false otherwise.
 * @author Raafat
 */
function accountActivation($user_id)
{
    $user = getFullUserById($user_id);
    return sendActivationMail($user["email"], $user_id, updateCode($user_id));
}

/**
 * Activates the user account by setting the code to 0
 * @param integer $id The unique ID of the user.
 * @return boolean True if the code was updated in the database and false otherwise.
 * @author Raafat
 */
function activateAccount($id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user` SET `activation_code` = '0' WHERE `user_id` = :id");
    $stmt->bindValue(":id", $id);
    return $stmt->execute();
}

/**
 * Function to set the activation to any specified value.
 * @param integer $id The unique ID of the user.
 * @param string $code The activation code to be used.
 * @return boolean True if the update operation in the database was successful and false otherwise.
 * @author Raafat
 */
function setActivationCode($id, $code)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user` SET `activation_code` = :code WHERE `user_id` = :id");
    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":code", $code);
    return $stmt->execute();
}

/**
 * Validates the user email, updates the activation code of the user and sends a reset password email.
 * @param string $email The unique email address of the user.
 * @return boolean True if the email was sent and false otherwise.
 * @author Raafat
 */
function resetPassword($email)
{
    $email = fixEmail($email);
    if (!userExists($email)) {
        return ACCOUNT_NOT_FOUND_ERR;
    }
    $u = getUserIdByEmail($email);
    $user_id = $u["user_id"];
    $code = updateCode($user_id);
    return sendResetMail($email, $user_id, $code);
}

/**
 * Sets the password of any given user to the specified value.
 * @param integer $user_id The unique ID of the user.
 * @param string $hashed_password The hashed password to be set.
 * @return boolean True if the update operation was successful and false otherwise.
 * @author Raafat
 */
function changePassword($user_id, $hashed_password)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user` SET `password` = :password WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":password", $hashed_password);
    return $stmt->execute();
}

function checkPassword($password, $confirm_password)
{
    return true;
}

function getProfilePicPath($id)
{
    return PROFILE_PIC_PATH . $id . ".png";
}

function getTempPath($data)
{
    return TEMP_DIR_PATH . $data . ".png";
}

function moveFile($file, $temp_path, $path)
{
    imagepng(imagecreatefromstring(file_get_contents($_FILES[$file]["tmp_name"])), $temp_path);
    rename($temp_path, $path);
}

function isValidImg($file)
{
    $info = getimagesize($_FILES[$file]["tmp_name"]);
    if ($info === false) {
        return false;
    }
    # supports gif, jpg and png only!
    if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
        return false;
    }
    return true;
}

/**
 * Checks whether given code is correct for the given user identified by user_id.
 * @param integer $id The unique ID of the user.
 * @param string $code The code to be checked.
 * @return boolean True if this code belongs to this user and false otherwise.
 * @author Raafat
 */
function checkCodeAndId($id, $code)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `user_id` = :id AND `activation_code` = :code");
    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":code", $code);
    $stmt->execute();
    return $stmt->rowCount() === 1;
}

###################################################################################################
###################################################################################################
###################################################################################################

function checkContactUs($email, $name, $subject, $comments)
{
    $email = fixEmail($email);
    if (!isValidEmail($email)) {
        return INVALID_EMAIL_ERR;
    }
    $name = fixName($name);
    if (!isValidName($name)) {
        return INVALID_NAME_ERR;
    }
    $subject = fixTitleCase($subject);
    global $CONTACT_US_SUBJECTS;
    if (!in_array($subject, $CONTACT_US_SUBJECTS)) {
        return INVALID_CONTACT_US_SUBJECT;
    }
    $comments = trim($comments);
    if ($comments === "") {
        return INVALID_COMMENTS_ERR;
    }
    return sendContactUsEmail($email, $name, $subject, $comments);
}


###################################################################################################
###################################################################################################
###################################################################################################

function jgetTutors($course)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `first_name`, `last_name`, `email`, `phone_number`, tutor.* FROM `tutor_courses` NATURAL JOIN `tutor` JOIN `user` ON `user_id` = tutor.tutor_id WHERE CONCAT(`subject_name`, ' ', `course_number`) = :course");
    $stmt->bindValue(":course", $course);
    $stmt->execute();
    $tutorsInfo = $stmt->fetchAll();
    if ($stmt->rowCount() > 0) {
        return json_encode($tutorsInfo, JSON_PRETTY_PRINT);
    } else {
        return "";
    }
}

function jsearchCourses($course)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `course` WHERE CONCAT(`subject_name`, ' ', `course_number`) LIKE '{$course}%' OR CONCAT(`subject_name`, ' ', `course_number`) LIKE '%{$course}'");
    //$stmt->bindParam(":course", $course);
    $stmt->execute();
    $coursesInfo = $stmt->fetchAll();
    if ($stmt->rowCount() > 0) {
        return json_encode($coursesInfo, JSON_PRETTY_PRINT);
    } else {
        return "";
    }
}

function jgetTutorSchedule($tutor_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `session` WHERE `tutor_id` = :tutor_id");
    $stmt->bindParam(":tutor_id", $tutor_id);
    $stmt->execute();
    $tutorSchedule = $stmt->fetchAll();
    return json_encode($tutorSchedule, JSON_PRETTY_PRINT);
}

function getSubjects()
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `subject_name` FROM `subject`");
    $stmt->execute();
    $info = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if ($stmt->rowCount() > 0) {
        return $info;
    } else {
        return null;
    }
}

function getSubjectCourses($subject)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `course_number` FROM `course` WHERE `subject_name` = :subject");
    $stmt->bindValue(":subject", $subject);
    $stmt->execute();
    $info = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if ($stmt->rowCount() > 0) {
        return $info;
    } else {
        return null;
    }
}

function getCourses()
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `course`");
    $stmt->execute();
    $info = $stmt->fetchAll();
    if ($stmt->rowCount() > 0) {
        return $info;
    } else {
        return null;
    }
}

###################################################################################################
###################################################################################################
###################################################################################################

function checkLoggedIn()
{
    if (!isLoggedIn()) {
        redirect("index?return_url=" . $_SERVER["REQUEST_URI"], NOT_LOGGED_IN);
    }
}

function isLoggedIn()
{
    if (isset($_SESSION["user_id"])) {
        return true;
    }
    return false;
}

function redirectOut()
{
    header("Location: index");
    exit;
}

function redirectIn()
{
    header("Location: home");
    exit;
}

function redirect($location, $flash_msg = null)
{
    if ($location === "home") { # login with return url!
        if (isset($_GET["return_url"])) {
            header("Location: " . $_GET["return_url"]);
            exit;
        }
    }
    if ($flash_msg) {
        $_SESSION["flash_msg"] = $flash_msg;
    }
    header("Location: " . $location);
    exit;
}

function displayFlashMsg()
{
    if (isset($_SESSION["flash_msg"])) {
        require_once("views/flash_msg.html");
    }
    unset($_SESSION["flash_msg"]);
}

function displayNotice($notice_type = 'success')
{
    if (isset($_SESSION["notice"])) {
        require_once("views/notice.html");
    }
    unset($_SESSION["notice"]);
}

# TODO: this needs improvement
function escapeChars($str)
{
    return htmlentities(addslashes(trim($str)));
}

# localhost/tutorme/reset_password?id=x&code=abc
#  return   reset_password?id=x&code=abc
function getPageURI()
{
    # TODO: this is ugly.
    $uri = $_SERVER["REQUEST_URI"];
    return substr($uri, strpos($uri, '/', strpos($uri, '/') + 1) + 1);
}

###################################################################################################
###################################################################################################
###################################################################################################

function checkAdmin($email, $password)
{
    global $ADMIN_ACCOUNTS, $ADMIN_PASSWORDS;
    if (in_array($email, $ADMIN_ACCOUNTS) && in_array($password, $ADMIN_PASSWORDS)) {
        return true;
    }
    return false;
}

function checkAdminLoggedIn()
{
    if (!isset($_SESSION["admin"])) {
        header("Location: logout");
        exit;
    }
}

###################################################################################################
###################################################################################################
###################################################################################################

function checkLoginRememberMe($email)
{
    if (isset($_POST["remember_me"]) && $_POST["remember_me"] === "remember_me") {
        setcookie("remember_me_email", $email);
    } else {
        setcookie("remember_me_email", "", time() - 3600);
        unset($_COOKIE["remember_me_email"]);
    }
}

function checkRemembered()
{
    if (isset($_COOKIE["remember_me_email"])) {
        return "checked";
    }
    return "";
}

function getRememberMeEmail()
{
    if (checkRemembered() === "checked") {
        return $_COOKIE["remember_me_email"];
    } else {
        return "";
    }
}

?>
