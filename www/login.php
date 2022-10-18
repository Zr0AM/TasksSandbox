<?php
session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);
// TODO
// https://www.w3schools.com/php/showphp.asp?filename=demo_form_validation_special
// $error = '';

require_once 'sql/scripts.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username'])) {

        $_SESSION['errorMsg'] = 'Username and password must be provided';
    } else {
        $username = sql\getPostVar('username');
    }

    if (empty($_POST['password'])) {

        $_SESSION['errorMsg'] = 'Username and password must be provided';
    } else {
        $password = sql\getPostVar('password');
    }

    if (! empty($_POST['username']) && ! empty($_POST['password'])) {

        $loginStatus = sql\processLogon($username, $password);

        if ($loginStatus) {

            header('Location: task_queue.php');
            exit();
        } elseif ($loginStatus == 'inactive') {

            $_SESSION['errorMsg'] = 'The provided account has expired';
        } else {

            $_SESSION['errorMsg'] = 'Username or password is invalid';
        }
    }
}

?>

<form
	action=<?php echo '"' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"';?>
	method="post">

	Username<br> <input type="text" name="username" id="username" autofocus
		autocomplete="username"><br> Password<br> <input type="password"
		name="password" id="password" autocomplete="current-password"><br> <br>
	<input class="in-line-text" type="submit" value="Sign-in" id="submit"><br>
	<br> <span><?php echo sql\getSessVar('errorMsg'); ?></span>

</form>

<?php $objHtml::writeFooter();?>
