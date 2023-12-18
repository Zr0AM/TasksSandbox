<?php
namespace sql;

abstract class scripts
{
    
    private const TIMEOUT = 600;

    public static function getPostVar($var)
    {
        if (isset($_POST[$var])) {

            $output = $_POST[$var];
            $output = strip_tags($output);
            $output = htmlentities($output);
            return stripslashes($output);
        }
    }

    public static function getSessVar($var)
    {
        if (isset($_SESSION[$var])) {

            $output = $_SESSION[$var];
            $output = strip_tags($output);
            $output = htmlentities($output);
            $output = stripslashes($output);

            unset($_SESSION[$var]);

            return $output;
        }
    }

    public function getFormattedDate($value)
    {
        return date('n/j/Y g:i:s A', strtotime($value));
    }

    public function alert($var)
    {
        echo "<script type='text/javascript'>alert(" . $var . ");</script>";
    }

    public function updateLastActivity()
    {

        if (! isset($_SESSION['userid']) || ! isset($_SESSION['LAST_ACTIVITY'])) {

            return;
        }

        if ((time() - $_SESSION['LAST_ACTIVITY'] <= self::TIMEOUT)) {

            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
            return;
        }

        self::add_log($_SESSION['userid'], 'Session expired');

        session_unset();
        session_destroy();
        session_start();

        $_SESSION['errorMsg'] = "Your session has expired";

        header('Location: ../login.php');
    }

    public static function processLogon($username, $password)
    {
        $success = false;

        $conn = self::connectDB();

        if ($conn->connect_error) {
            $conn->close();

            self::add_log($username, 'Login connection error');

            return $success;
        }

        self::add_log($username, 'Login attempt');

        $user = array();

        $stmt = $conn->prepare('SELECT user_id, user_name, user_password, user_active FROM tbl_user WHERE user_id = ?');

        $stmt->bind_param('s', $username);

        $stmt->execute();

        $stmt->bind_result($user['id'], $user['name'], $user['password'], $user['active']);

        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $conn->close();

            self::add_log($username, 'Login user does not exist');

            return $success;
        }

        $stmt->fetch();

        if (! $user['active']) {

            $conn->close();

            self::add_log($username, 'Login user inactive');

            return $success;
        }

        if (! password_verify($password, $user['password'])) {

            $conn->close();

            self::add_log($username, 'Login wrong password');

            return $success;
        }

        // update user last login
        $sql = 'UPDATE tbl_user SET user_last_sign_on = NOW()  WHERE user_id = "' . $user['id'] . '"';

        $result = $conn->query($sql);

        $conn->close();

        if (! $result) {

            self::add_log($username, 'Login issue');

            return $success;
        }

        $_SESSION['userid'] = $user['id'];
        $_SESSION['username'] = $user['name'];

        self::add_log($username, 'Login success');

        $success = true;
        return $success;
    }

    public static function add_log($user, $action)
    {
        $conn = self::connectDB();

        if ($conn->connect_error) {
            $conn->close();
            return false;
        }

        if (isset($_SESSION['userid'])) {

            $user = $_SESSION['userid'];
        }

        $sql = 'CALL add_log("' . $user . '","' . $action . '")';

        $result = $conn->query($sql);

        $conn->close();

        return $result;
    }

    public static function connectDB()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/util/Config.php";

        // require $temp;
        require_once \util\Config::configFile("Connect.php");

        return mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
    }
}
?>
