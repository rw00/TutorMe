<?php
/**
 * @author Raafat
 */

require_once("InfoManager.php");

function getFullUser($email, $password)
{
    $hashed_password = getUserHashedPassword($email);
    if ($hashed_password !== null) { # user exists!
        if (password_verify($password, $hashed_password)) { # correct password
            $conn = DBManager::getConn();
            $stmt = $conn->prepare("SELECT * FROM `user` WHERE `email` = :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            return $stmt->fetch();
        }
    }
    return null;
}

function getFullUserById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $r = $stmt->fetch();
        return getMoreUserInfo($user_id, $r);
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getMoreUserInfo($user_id, $user_info)
{
    $type = getUserTypeById($user_id);
    if ($type === "Student") {
        return array_merge($user_info, getStudentInfoById($user_id));
    } else if ($type === "Tutor") {
        return array_merge($user_info, getTutorInfoById($user_id));
    }
    return null;
}

function getStudentInfoById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `student` WHERE `student_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    return $stmt->fetch();
}

function getTutorInfoById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `tutor` WHERE `tutor_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    return $stmt->fetch();
}

function getUserTypeById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `student` WHERE `student_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return "Student";
    }
    $stmt = $conn->prepare("SELECT * FROM `tutor` WHERE `tutor_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return "Tutor";
    }
    return null;
}

###################################################################################################

function getUserById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `first_name`, `last_name`, `email`, `phone_number`, `gender` FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getUserByEmail($email)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `first_name`, `last_name`, `email`, `phone_number`, `gender` FROM `user` WHERE `email` = :email");
    $stmt->bindValue(":email", fixEmail($email));
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getUser($email, $password)
{
    $hashed_password = getUserHashedPassword($email);
    if ($hashed_password !== null) { # user exists!
        if (password_verify($password, $hashed_password)) { # correct password
            $conn = DBManager::getConn();
            $stmt = $conn->prepare("SELECT `first_name`, `last_name`, `email`, `phone_number`, `gender` FROM `user` WHERE `email` = :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            return $stmt->fetch();
        }
    }
    /* check for rehash */
    return null;
}

function getUserHashedPassword($email)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `password` FROM `user` WHERE `email` = :email");
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch()["password"];
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getUserIdByEmail($email)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `email` = :email");
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch(PDO::FETCH_COLUMN, 0);
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

###################################################################################################

function userExists($email)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `email` = :user_email");
    $stmt->bindValue(":user_email", $email);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } elseif ($stmt->rowCount() === 0) {
        return false;
    }
}

function userExistsById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } elseif ($stmt->rowCount() === 0) {
        return false;
    }
}

function getFullNameById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $info = $stmt->fetch();
        return $info["first_name"] . " " . $info["last_name"];
    } else {
        return "";
    }
}

function getInitialsById($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `first_name`, `last_name` FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $info = $stmt->fetch();
        return fixInitials($info["first_name"], $info["last_name"]);
    } else {
        return "";
    }
}

function activeUser($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `activation_code` FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $info = $stmt->fetch();
        if ($info["activation_code"] === "0") {
            return true;
        } else {
            return false;
        }
    } elseif ($stmt->rowCount() === 0) {
        return false;
    }
}

###################################################################################################

function insertUser($user)
{ # data is validated and fixed!
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("INSERT INTO `user` (`email`, `password`, `first_name`, `last_name`, `phone_number`, `activation_code`, `gender`) VALUES (:email, :password, :first_name, :last_name, :phone_number, :activation_code, :gender)");
    $stmt->bindValue(":email", $user["email"]);
    $stmt->bindValue(":password", hashPassword($user["password"]));
    $stmt->bindValue(":first_name", $user["first_name"]);
    $stmt->bindValue(":last_name", $user["last_name"]);
    $stmt->bindValue(":phone_number", $user["phone_number"]);
    $stmt->bindValue(":gender", $user["gender"]);
    $stmt->bindValue(":activation_code", getCode());
    # TODO !
    # change this
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $conn->lastInsertId();
    } else {
        return false;
    }
}

function insertInto($account_type, $user_id)
{
    $account_type = strtolower($account_type);
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("INSERT INTO `{$account_type}` (`{$account_type}_id`) VALUES (:user_id)");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
}

function updateUser($user_id, $user)
{ # data is validated and fixed
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user` SET `email` = :email, `password` = :password, `first_name` = :first_name, `last_name` = :last_name, `phone_number` = :phone_number, `address` = :address WHERE `user_id` = :user_id");
    $stmt->bindValue(":email", $user["email"]);
    $stmt->bindValue(":password", hashPassword($user["password"]));
    $stmt->bindValue(":first_name", $user["first_name"]);
    $stmt->bindValue(":last_name", $user["last_name"]);
    $stmt->bindValue(":phone_number", $user["phone_number"]);
    $stmt->bindValue(":address", $user["address"]);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } else {
        return false;
    }
}

function deleteUser($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("DELETE FROM `user` WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } else {
        return false;
    }
}

function changeProfilePic($user_id, $path)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user` SET `profile_pic` = :profile_pic WHERE `user_id` = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":profile_pic", $path);
    $stmt->execute();
}

?>
