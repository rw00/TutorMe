# TutorMe
TutorMe webapp. The idea is that students can search for tutors and book private lessons appointments to study for their courses.

This project was firstly developed as a Java Swing Desktop application for Software Engineering course between March and April 2015. 
Now, I'm trying to rewrite it as a PHP web app.

INSTALLATION
------------
1. Run MySQL Server on default port 3306 with the following credentials:
	username: root
	password: root
	
	Or you may change the database connection settings in php/classes/DBManager.php
2. Load the "tutorme.sql" into MySQL and execute it.
3. Start WampServer and run on default port 80.
4. Edit php/classes/Mailing.php for SMTP settings.
5. Copy the entire folder "tutorme" to "wamp/www/"
6. Browse to	localhost/tutorme/


Note that you need to allow the HTACCESS configuration from Apache by enabling rewrite_mod.

---------------------------------------------------------------------------------------------------
DISCLAIMER: Third party libraries or assets such as BootstrapDialog or quickPagination include 
	and use the licenses of their respective developers.

---------------------------------------------------------------------------------------------------
	- Developed and Prepared by RW.
