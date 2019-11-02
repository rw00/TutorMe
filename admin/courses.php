<?php $page_title = "Manage Courses";
require_once("../html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkAdminLoggedIn();

require_once("admin_includes/admin_nav.php"); ?>

<div id="content">
    <div id="main-content">
        <div class="centered">
            <?php require_once(CLASSES_PATH . "AdminDataManager.php"); ?>
            <h4>Courses Info</h4>
            <a href="#courses-info" class="show-more-content">Show Content</a>
            <div class="more-content">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Course Subject</th>
                        <th>Course Number</th>
                        <th>Course Title</th>
                    </tr>
                    <?php $courses_info = getCourses();
                    foreach ($courses_info as $course): ?>
                    <tr>
                        <td><?= $course["subject_name"] ?></td>
                        <td><?= $course["course_number"] ?></td>
                        <td><?= $course["course_title"] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <br />
            <br />

            <h4>Add Courses</h4>
            <?php
                if (isset($_POST["subject_name"], $_POST["course_number"], $_POST["course_title"])) {
                    $insertCourse = checkInsertCourse($_POST["subject_name"], $_POST["course_number"], $_POST["course_title"]);
                    if ($insertCourse === true) {
                        echo "Course added successfully!";
                    } else {
                        echo $insertCourse;
                    }
                }
                ?>

            <form action="courses" method="post" class="form-horizontal">
                <div class="row">
                    <label for="subject-name">Subject Name:</label>
                    <select id="subject-name" name="subject_name" class="form-control">
                        <?php $subjects = getSubjects();
                            foreach ($subjects as $subject): ?>
                        <option value="<?= $subject ?>"><?= $subject ?></option>
                        <?php
                            endforeach;
                            ?>
                    </select>
                </div>
                <div class="row">
                    <label for="course-number">Course Number:</label>
                    <input id="course-number" name="course_number" type="text" class="form-control" />
                </div>
                <div class="row">
                    <label for="course-title">Course Title:</label>
                    <input id="course-title" name="course_title" type="text" class="form-control" />
                </div>
                <br />
                <div class="row">
                    <input name="add_course_submit" type="submit" class="btn btn-default btn-block" />
                </div>
            </form>

            <br />
            <br />
            <h4>Delete Courses</h4>
            <?php
                if (isset($_POST["course"])) {
                    $deleteCourse = checkDeleteCourse($_POST["course"]);
                    if ($deleteCourse === true) {
                        echo "Course deleted successfully!";
                    } else {
                        echo $deleteCourse;
                    }
                }
                ?>
            <form action="courses" method="post" class="form-horizontal">
                <div class="row">
                    <label for="course">Course Number:</label>
                    <select id="course" name="course" class="form-control">
                        <option value="">--Select Course--</option>
                        <?php $subjects = getSubjects();
                            foreach ($subjects as $subject): ?>
                        <optgroup label="<?= $subject ?>">
                            <?php
                                    $courses = getSubjectCourses($subject);
                                    foreach ($courses as $course): ?>
                            <option value="<?= $subject . " " . $course ?>"><?= $subject . " " . $course ?></option>
                            <?php
                                    endforeach; ?>
                        </optgroup>
                        <?php
                            endforeach;
                            ?>
                    </select>
                </div>
                <br />
                <div class="row">
                    <input name="delete_course_submit" type="submit" class="btn btn-default btn-block" />
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once("../html/html_footer.html"); ?>
