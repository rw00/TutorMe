<?php
$page_redirected_from = $_SERVER['REQUEST_URI'];  // this is especially useful with error 404 to indicate the missing page.
$server_url = "http://" . $_SERVER["SERVER_NAME"] . "/";
$redirect_url = $_SERVER["REDIRECT_URL"];
$redirect_url_array = parse_url($redirect_url);
$end_of_path = strrchr($redirect_url_array["path"], "/");

switch (getenv("REDIRECT_STATUS")) {
    # 400 - Bad Request
    case 400:
        $error_code = "400 - Bad Request";
        $explanation = "The syntax of the URL submitted by your browser could not be understood.  Please verify the address and try again.";
        $redirect_to = "";
        break;

    # 401 - Unauthorized
    case 401:
        $error_code = "401 - Unauthorized";
        $explanation = "This section requires a password or is otherwise protected. Click <a href='index'>here</a> to go to the main page.";
        $redirect_to = "";
        break;

    # 403 - Forbidden
    case 403:
        $error_code = "403 - Forbidden";
        $explanation = "This section requires a password or is otherwise protected. Click <a href='index'>here</a> to go to the main page.";
        $redirect_to = "";
        break;

    # 404 - Not Found
    case 404:
        $error_code = "404 - Not Found";
        $explanation = "The requested resource '" . $page_redirected_from . "' could not be found on this server.  Please verify the address and try again.";
        $redirect_to = $server_url;
        break;

    # 500 - Internal Server Error
    case 500:
        $error_code = "500 - Internal Server Error";
        $explanation = "The server experienced an unexpected error.  Please try again later.";
        $redirect_to = "";
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon"/>
    <?php
    if ($redirect_to !== "") {
        ?>
        <meta http-equiv="Refresh" content="5; url='<?= $redirect_to ?>'">
        <?php
    }
    ?>
    <title>Error</title>
</head>

<body>
<h1>Error Code <?= $error_code ?></h1>

<p><strong>The error details:</strong>
    <?= $explanation ?>
</p>

<p>You will be automatically redirected to home page in 5 seconds.</p>

<hr>
</body>

</html>
