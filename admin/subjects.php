<?php $page_title = "Manage Subjects";
require_once("../html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkAdminLoggedIn();

require_once("admin_includes/admin_nav.php"); ?>

<div id="content">
    <div id="main-content">
        <div class="centered">
            <?php require_once(CLASSES_PATH . "AdminDataManager.php"); ?>
            <h4>Add Subject</h4>
            <?php
            if (isset($_POST["subject_name"], $_POST["subject_title"])) {
                $insertSubject = checkInsertSubject($_POST["subject_name"], $_POST["subject_title"]);
                if ($insertSubject === true) {
                    echo "Subject added successfully!";
                } else {
                    echo $insertSubject;
                }
            }
            ?>
            <form action="subjects" method="post" class="form-horizontal">
                <div class="row">
                    <label for="subject-name">Subject Name:</label>
                    <input id="subject-name" name="subject_name" type="text" class="form-control"/>
                </div>
                <div class="row">
                    <label for="subject-title">Subject Title:</label>
                    <input id="subject-title" name="subject_title" type="text" class="form-control"/>
                </div>
                <br/>
                <div class="row">
                    <input name="add_subject_submit" type="submit" class="btn btn-default btn-block"/>
                </div>
            </form>

            <br/>
            <br/>
            <h4>Delete Subject</h4>
            <?php
            if (isset($_POST["subject"])) {
                $deleteSubject = checkDeleteSubject($_POST["subject"]);
                if ($deleteSubject === true) {
                    echo "Subject deleted successfully!";
                } else {
                    echo $deleteSubject;
                }
            }
            ?>
            <form action="subjects" method="post" class="form-horizontal">
                <div class="row">
                    <label for="subject">Subject Name:</label>
                    <select id="subject" name="subject" class="form-control">
                        <option value="">--Select Subject--</option>
                        <?php $subjects = getSubjects();
                        foreach ($subjects as $subject): ?>
                            <option value="<?= $subject ?>"><?= $subject ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
                <br/>
                <div class="row">
                    <input name="delete_subject_submit" type="submit" class="btn btn-default btn-block"/>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once("../html/html_footer.html"); ?>
