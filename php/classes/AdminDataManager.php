<?php
require_once("DBManager.php");
require_once("Helper.php");

/**
 * Returns true if the subject exists based on subject name and false otherwise.
 * @param string $subject_name The subject based on a shortcode name.
 * @return bool If subject exists return true and false otherwise.
 * @author Raafat
 */
function subjectExists($subject_name)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `subject` WHERE `subject_name` = :subject");
    $stmt->bindValue(":subject", $subject_name);
    $stmt->execute();
    return $stmt->rowCount() === 1 ? true : false;
}

function checkInsertSubject($subject_name, $subject_title)
{
    $subject_name = fixSubjectName($subject_name);
    $subject_title = fixTitleCase($subject_title);
    if (subjectExists($subject_name)) {
        return "Invalid SubjectName; already exists.";
    }

    insertSubject($subject_name, $subject_title);
    return true;
}

function checkDeleteSubject($subject_name)
{
    $subject_name = fixSubjectName($subject_name);
    deleteSubject($subject_name);
    return true;
}

function insertSubject($subject_name, $subject_title)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("INSERT INTO `subject` (`subject_name`, `subject_title`) VALUES (:subject, :subject_title)");
    $stmt->bindValue(":subject", $subject_name);
    $stmt->bindValue(":subject_title", $subject_title);
    $stmt->execute();
}

function deleteSubject($subject_name)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("DELETE FROM `subject` WHERE `subject_name` = :subject");
    $stmt->bindValue(":subject", $subject_name);
    $stmt->execute();
}

###################################################################################################
###################################################################################################
###################################################################################################

function checkInsertCourse($subject_name, $course_number, $course_title)
{
    $subject_name = fixSubjectName($subject_name);
    $course_number = fixSubjectName($course_number);
    $course_title = fixTitleCase($course_title);
    if (!subjectExists($subject_name)) {
        return "Invalid SubjectName; does not exist.";
    }
    insertCourse($subject_name, $course_number, $course_title);
    return true; # could use some more work
}

function checkDeleteCourse($course)
{
    list($subject_name, $course_number) = explode(" ", $course);
    $subject_name = fixSubjectName($subject_name);
    $course_number = fixSubjectName($course_number);
    deleteCourse($subject_name, $course_number);
    return true;
}

function insertCourse($subject_name, $course_number, $course_title)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("INSERT INTO `course` (`subject_name`, `course_number`, `course_title`) VALUES (:subject, :course_number, :course_title)");
    $stmt->bindValue(":subject", $subject_name);
    $stmt->bindValue(":course_number", $course_number);
    $stmt->bindValue(":course_title", $course_title);
    $stmt->execute();
}

function updateCourse($subject_name, $course_number, $fix_course_number, $fix_course_title)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `course` SET `course_number` = :fix_course_number, `course_title` = :fix_course_title WHERE `subject_name` = :subject AND `course_number` = :course_number");
    $stmt->bindValue(":subject", $subject_name);
    $stmt->bindValue(":course_number", $course_number);
    $stmt->bindValue(":fix_course_number", $fix_course_number);
    $stmt->bindValue(":fix_course_title", $fix_course_title);
    $stmt->execute();
}

function deleteCourse($subject_name, $course_number)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("DELETE FROM `course` WHERE `subject_name` = :subject AND `course_number` = :course_number");
    $stmt->bindValue(":subject", $subject_name);
    $stmt->bindValue(":course_number", $course_number);
    $stmt->execute();
}

###################################################################################################
###################################################################################################
###################################################################################################

function getUsers()
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `user_id`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `is_blocked`, `is_online` FROM `user`");
    $stmt->execute();
    $users = $stmt->fetchAll();
    $users_info = array();
    foreach ($users as $user) {
        $users_info[] = array_merge($user, array("account_type" => getUserAccountTypeById($user["user_id"])));
    }
    return $users_info;
}

function getUserAccountTypeById($user_id)
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
###################################################################################################
###################################################################################################

# fixes helper functions for subjects, courses
function fixSubjectName($subject)
{
    return strtoupper(trim($subject));
}

/*
function fixTitleCase($str) {
    return ucwords(strtolower(trim($str)));
}
*/
