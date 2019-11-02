<?php
define("MIN_PASS_LEN", 8);

function fixName($name)
{
    return ucfirst(strtolower(trim($name)));
}

/**
 * Returns the string as trimmed Title Case.
 * @param string $s The string to be fixed.
 * @return string The fixed string as trimmed Title Case.
 * @author Raafat
 */
function fixTitleCase($s)
{
    return ucwords(strtolower(trim($s)));
}

/**
 * Returns the email address string with lowercase characters and trimmed of leading and trailing whitespaces.
 * @param string $email The email to be fixed.
 * @return string The fixed email address as lowercase and trimmed.
 * @author Raafat
 */
function fixEmail($email)
{
    return strtolower(trim($email));
}

/**
 * Returns the capitalized initials of the first name and last name strings.
 * @param string $fname The first name to get its initial.
 * @param string $lname The last name to get its first character.
 * @return string The upper case characters of the initials of the first and last names.
 * @author Raafat
 */
function fixInitials($fname, $lname)
{
    return strtoupper(substr(trim($fname), 0, 1) . "" . substr(trim($lname), 0, 1));
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidName($name)
{
    return preg_match("/^[A-Za-z]+$/", $name);
}

function isValidPhoneNumber($phone_number)
{
    return preg_match("/^[\d]{8,15}$/", $phone_number);
}

function isValidPassword($user_password)
{
    if (strlen($user_password) >= MIN_PASS_LEN && strpos($user_password, " ") === false) {
        return true;
    }
    return false;
}

function validPasswords($password, $confirm_password)
{
    return isValidPassword($password) && isValidPassword($confirm_password);
}

# improve func later
function isNum($s)
{
    return is_numeric($s) && is_int((int)$s);
}

/**
 * Checks whether code or activation code is valid. i.e the code was updated.
 * @param string $code The code to be checked
 * @return bool True if $code is not 0 and is not -1. False otherwise.
 * @author Raafat
 */
function isValidCode($code)
{
    return $code !== "0" && $code !== "-1";
}

# in case we decide to change the algo
function getCode()
{
    return hash("sha256", uniqid(rand(), true), false);
}

# in case we want to upgrade our password hashing algo
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 8]);
}

?>
