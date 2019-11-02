<?php
$page_title = "Contact Us";
require_once("html/html_header.html"); ?>

    <div class="my-nav"></div>

    <div id="content">
        <div id="main-content">
            <div class="centered">
                <?php
                if (isset($_POST["email"], $_POST["name"], $_POST["subject"], $_POST["comment"])) {
                    require_once(CLASSES_PATH . "InfoManager.php");
                    $contactform = checkContactUs($_POST["email"], $_POST["name"], $_POST["subject"], $_POST["comment"]);
                    if (is_string($contactform)) { ?>
                        <p class="text-danger"><?= $contactform ?></p>
                        <?php
                    } else if ($contactform === false) { ?>
                        <p class="text-danger"><?= CONTACT_US_FAIL ?></p>
                        <?php
                    } else { ?>
                        <p class="text-success"><?= CONTACT_US_SUCCESS ?></p>
                        <?php
                    }
                }
                ?>
                <br/>
                <form action="contact" method="post" id="contact-form" class="form-horizontal">
                    <div class="form-group">
                        <label for="name">Your Name *:</label>
                        <input id="name" name="name" type="text" class="form-control" placeholder="Name" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email *:</label>
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *:</label>
                        <select id="subject" name="subject" class="form-control">
                            <option value="Bug Report">Bug Report</option>
                            <option value="Recommendation">Recommendation</option>
                            <option value="Job Application">Job Application</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comments *:</label>
                        <textarea id="comment" name="comment" placeholder="Enter your comments here..." class="form-control" rows="5" required="required"></textarea>
                    </div>
                    <div class="form-group">
                        <input id="send" type="submit" value="Send" class="form-control btn btn-block btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="confirmbox" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Send?</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="pull-right">
                        <button id="cancel-btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="ok-btn" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-footer"></div>

<?php require_once("html/ng_scripts.html"); ?>

<?php require_once("html/html_footer.html"); ?>
