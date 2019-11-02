DROP DATABASE IF EXISTS `tutorme`;
CREATE DATABASE `tutorme`;
USE `tutorme`;

CREATE TABLE `user` (
    `user_id` BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone_number` VARCHAR(255),
    `password` VARCHAR(255) NOT NULL,
    `gender` VARCHAR(255) NOT NULL,
	`address` VARCHAR(255),
	`is_blocked` TINYINT NOT NULL DEFAULT 0,
	`is_online` TINYINT NOT NULL DEFAULT 0,
    `profile_pic` VARCHAR(255) NOT NULL DEFAULT 'user_data/profile_pic/user.jpg',
    `activation_code` VARCHAR(255) NOT NULL DEFAULT '-1'
) AUTO_INCREMENT=100000;

CREATE TABLE `tutor` (
    `tutor_id` BIGINT NOT NULL PRIMARY KEY,
    
	FOREIGN KEY (`tutor_id`)
        REFERENCES `user` (`user_id`)
        ON DELETE CASCADE
);

CREATE TABLE `student` (
    `student_id` BIGINT NOT NULL PRIMARY KEY,
    
	FOREIGN KEY (`student_id`)
        REFERENCES `user` (`user_id`)
        ON DELETE CASCADE
);

CREATE TABLE `subject` (
    `subject_name` VARCHAR(255) NOT NULL PRIMARY KEY,
    `subject_title` VARCHAR(255) NOT NULL
);

CREATE TABLE `course` (
    `subject_name` VARCHAR(255) NOT NULL,
    `course_number` INT NOT NULL,
	`course_title` VARCHAR(255) NOT NULL,
	
	PRIMARY KEY (`subject_name`, `course_number`), 
	
	FOREIGN KEY (`subject_name`)
        REFERENCES `subject` (`subject_name`)
        ON DELETE CASCADE
);

CREATE TABLE `tutor_courses` (
	`tutor_id` BIGINT NOT NULL, 
	`subject_name` VARCHAR(255) NOT NULL, 
	`course_number` VARCHAR(255) NOT NULL, 
	
	FOREIGN KEY (`tutor_id`)
		REFERENCES `tutor` (`tutor_id`), 
	FOREIGN KEY (`subject_name`)
		REFERENCES `course` (`subject_name`), 
	FOREIGN KEY (`course_number`)
		REFERENCES `course` (`course_number`)
);

CREATE TABLE `message` (
    `message_id` BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `msg_text` TEXT NOT NULL,
    `msg_date` DATETIME NOT NULL
) AUTO_INCREMENT=100000;

CREATE TABLE `user_messages` (
    `user_message_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `message_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `other_user_id` INT NOT NULL,
    `folder` ENUM('Inbox', 'Sent') NOT NULL DEFAULT 'Inbox',
    `unread` BOOL NOT NULL DEFAULT TRUE,
    `deleted` ENUM('None', 'Deleted') NOT NULL DEFAULT 'None',
    FOREIGN KEY (`message_id`)
        REFERENCES message (`message_id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`user_id`)
        REFERENCES user (`user_id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`other_user_id`)
        REFERENCES user (`user_id`)
        ON DELETE CASCADE
) AUTO_INCREMENT=100000;

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `gender`, `address`, `activation_code`) VALUES (100000, 'R', 'W', 'raafatwahb@gmail.com', '961710000000', '$2y$08$HdKjDujrODjMpHGxCs1sn.LQc3bR1GJRiTnIRP5KaaTjTVBv0bFNa', 'Male', 'Beirut', '0');
INSERT INTO `student` (`student_id`) VALUES (100000);

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `gender`, `address`, `activation_code`) VALUES (100001, 'A', 'B', 'abc@gmail.com', '96170000000', '$2y$08$HdKjDujrODjMpHGxCs1sn.LQc3bR1GJRiTnIRP5KaaTjTVBv0bFNa', 'Male', 'Beirut', '0');
INSERT INTO `tutor` (`tutor_id`) VALUES (100001);

INSERT INTO `subject` (`subject_name`, `subject_title`) VALUES ('CMPS', 'Computer Science');
INSERT INTO `subject` (`subject_name`, `subject_title`) VALUES ('MATH', 'Mathematics');
INSERT INTO `course` (`subject_name`, `course_number`, `course_title`) VALUES ('CMPS', 200, 'Intro to Programming');
INSERT INTO `course` (`subject_name`, `course_number`, `course_title`) VALUES ('MATH', 201, 'Calculus');