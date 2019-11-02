<?php
$page_title = "TutorMe Account Activation";
require_once("html/html_min_header.html"); ?>

    <div id="full-page-content">
        <h4 class="text-center"><span class="title">TutorMe</span> Account Activation</h4>
        <p class="main-text">
            <?php
            require_once(CLASSES_PATH . "InfoManager.php");
            # improve security here!
            # && $_GET["code"] !== "-1"
            if (isset($_GET["id"], $_GET["code"])):
                if (checkCodeAndId($_GET["id"], $_GET["code"]) === true):
                    activateAccount($_GET["id"]);
                    echo "Your account is now successfully activated!" . $redirect_script;
                else:
                    echo INVALID_INFO_ERR;
                endif;
            else:
                echo INVALID_INFO_ERR;
            endif;
            ?>
        </p>
    </div>

<?php require_once("html/html_footer.html"); ?>
