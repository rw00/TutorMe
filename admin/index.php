<?php $page_title = "Admin Panel | Users";
require_once("../html/html_header.html");

require_once(CLASSES_PATH . "InfoManager.php");
checkAdminLoggedIn();
require_once(CLASSES_PATH . "AdminDataManager.php");

require_once("admin_includes/admin_nav.php"); ?>

    <div id="content">
        <div id="main-content">
            <h4>Users Info</h4>
            <a href="#users-info" class="show-more-content">Show Content</a>
            <div class="more-content">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Account Type</th>
                        <th>Edit User</th>
                    </tr>
                    <?php $users_info = getUsers();
                    foreach ($users_info as $user): ?>
                        <tr>
                            <td><?= $user["user_id"] ?></td>
                            <td><?= $user["first_name"] ?></td>
                            <td><?= $user["last_name"] ?></td>
                            <td><?= $user["email"] ?></td>
                            <td><?= $user["gender"] ?></td>
                            <td><?= $user["account_type"] ?></td>
                            <td>
                                <form action="edit_user" method="get">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>"/>
                                    <button class="btn btn-default" type="submit">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;
                    ?>
                </table>
            </div>
        </div>
    </div>

<?php require_once("../html/html_footer.html"); ?>
