<?php
require_once("InfoManager.php");

function sendMessage($user_id, $other_user_id, $msg_text)
{
    if (userExistsById($user_id) === true && userExistsById($other_user_id) === true) {
        $msg_text = trim($msg_text);
        if ($msg_text === "") {
            return false;
        }
        $msg_id = insertMsg($msg_text);
        if (isNum($msg_id)) {
            insertUserMessage($msg_id, $user_id, $other_user_id, "Sent");
            insertUserMessage($msg_id, $other_user_id, $user_id, "Inbox");
            return true;
        }
        return false;
    } else {
        return false;
    }
}

function getMsgsWithOtherUser($user_id, $other_user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT * FROM `user_messages` NATURAL JOIN `message` WHERE `user_id` = :user_id AND `other_user_id` = :other_user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":other_user_id", $other_user_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function deleteMsg($user_id, $message_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user_messages` SET `deleted` = 'Deleted' WHERE `user_id` = :user_id AND `message_id` = :message_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT COUNT(`message_id`) FROM `user_messages` WHERE `message_id` = :message_id AND `deleted` = 'Deleted'");
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();
    $r = $stmt->fetch();
    if ($r["COUNT(message_id)"] === 2) {
        $stmt = $conn->prepare("DELETE FROM `message` WHERE `message_id` = :message_id");
        $stmt->bindValue(":message_id", $message_id);
        $stmt->execute();
    }
}

function isMySentMsg($user_id, $msg_id)
{
    return isMyMsg($user_id, $msg_id, "Sent");
}

function isMyInboxMsg($user_id, $msg_id)
{
    return isMyMsg($user_id, $msg_id, "Inbox");
}

function isMyMsg($user_id, $msg_id, $folder)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `user_id` FROM `user_messages` WHERE `folder` = '{$folder}' AND `user_id` = :user_id AND `message_id` = :msg_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":msg_id", $msg_id);
    $stmt->execute();
    return $stmt->rowCount() === 1 ? true : false;
}

function setReadMsg($msg_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("UPDATE `user_messages` SET `unread` = :unread WHERE `message_id` = :msg_id AND `folder` = 'Inbox'");
    $stmt->bindValue(":unread", 0);
    $stmt->bindValue(":msg_id", $msg_id);
    $stmt->execute();
}

function getMsgText($message_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT `msg_text` FROM `user_messages` userMsg NATURAL JOIN `message` WHERE userMsg.message_id = :message_id");
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result["msg_text"];
}

function getInboxMsgs($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT msg.message_id, `msg_text`, `first_name`, `last_name`, userMsg.other_user_id, `unread` FROM `message` msg JOIN `user_messages` userMsg JOIN `user` u
    ON msg.message_id = userMsg.message_id AND u.user_id = userMsg.other_user_id WHERE userMsg.deleted = 'None' AND userMsg.folder = 'Inbox' AND userMsg.user_id = :user_id ORDER BY unread DESC, msg_date DESC");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    $info = $stmt->fetchAll();
    $msgs = array();
    foreach ($info as $i) {
        $msgs[] = array_merge(array("unread" => $i["unread"]), getMsgData($i));
    }
    return $msgs;
}

function getSentMsgs($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT msg.message_id, `msg_text`, `first_name`, `last_name`, `other_user_id` FROM `message` msg JOIN `user_messages` userMsg JOIN `user` u
    ON msg.message_id = userMsg.message_id AND u.user_id = userMsg.other_user_id WHERE userMsg.deleted = 'None' AND userMsg.folder = 'Sent' AND userMsg.user_id = :user_id ORDER BY msg_date DESC");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    $info = $stmt->fetchAll();
    $msgs = array();
    foreach ($info as $i) {
        $msgs[] = getMsgData($i);
    }
    return $msgs;
}

function getMsgData($msg_data)
{
    return array("message_id" => $msg_data["message_id"], "first_name" => $msg_data["first_name"], "last_name" => $msg_data["last_name"], "msg_text" => $msg_data["msg_text"], "other_user_id" => $msg_data["other_user_id"]);
}

function getNumberOfUnreadMsgs($user_id)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("SELECT COUNT(msg.message_id) FROM `message` msg JOIN `user_messages` userMsg
    ON msg.message_id = userMsg.message_id WHERE `unread` = :unread AND userMsg.deleted = 'None' AND userMsg.folder = 'Inbox' AND userMsg.user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":unread", true);
    $stmt->execute();
    $info = $stmt->fetch();
    $numberOfUnreadMsgs = $info["COUNT(msg.message_id)"];
    return $numberOfUnreadMsgs;
}

function insertMsg($msg)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("INSERT INTO `message` (`msg_text`, `msg_date`) VALUES (:msg, NOW())");
    $stmt->bindValue(":msg", $msg);
    $stmt->execute();
    $message_id = $conn->lastInsertId();
    return (int)($message_id);
}

function insertUserMessage($message_id, $uId, $oId, $folder)
{
    $conn = DBManager::getConn();
    $stmt = $conn->prepare("INSERT INTO `user_messages` (`message_id`, `user_id`, `other_user_id`, `folder`) VALUES (:message_id, :uId, :oId, :folder)");
    $stmt->bindValue(":message_id", $message_id);
    $stmt->bindValue(":uId", $uId);
    $stmt->bindValue(":oId", $oId);
    $stmt->bindValue(":folder", $folder);
    $stmt->execute();
}

?>
