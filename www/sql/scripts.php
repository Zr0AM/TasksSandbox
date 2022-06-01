<?php
namespace sql;

function getPostVar($var)
{
  
  if (isset($_POST[$var]))
  {
    
    $output = $_POST[$var];
    $output = strip_tags($output);
    $output = htmlentities($output);
    return stripslashes($output);
  }
  
}

function getSessVar($var)
{
  
  if (isset($_SESSION[$var]))
  {
    
    $output = $_SESSION[$var];
    $output = strip_tags($output);
    $output = htmlentities($output);
    $output = stripslashes($output);
    
    unset($_SESSION[$var]);
    
    return $output;
  }
  
}

function getFormattedDate($value)
{
  
  return date('n/j/Y g:i:s A', strtotime($value));
  
}

function alert($var)
{
  
  echo "<script type='text/javascript'>alert(" . $var . ");</script>";
  
}

function updateLastActivity()
{
  
  define("TIMEOUT", 600);
  
  if (isset($_SESSION['userid']) && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > TIMEOUT))
  {
    
    add_log($_SESSION['userid'], 'Session expired');
    
    session_unset();
    session_destroy();
    session_start();
    
    $_SESSION['errorMsg'] = "Your session has expired";
    
    header('Location: ../login.php');
    exit;
  }
  else if (isset($_SESSION['userid']))
  {
    
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  }
  
}

function processLogon($username, $password)
{
  
  require_once 'connect.php';
  $success = false;
  
  $conn = connectDB();
  
  if ($conn->connect_error)
  {
    $conn->close();
    
    add_log($username, 'Login connection error');
    
    goto label_exit;
  }
  
  add_log($username, 'Login attempt');
  
  $user = array();
  
  $stmt = $conn->prepare('SELECT user_id, user_name, user_password, user_active FROM tbl_user WHERE user_id = ?');
  
  $stmt->bind_param('s', $username);
  
  $stmt->execute();
  
  $stmt->bind_result($user['id'], $user['name'], $user['password'], $user['active']);
  
  $stmt->store_result();
  
  if ($stmt->num_rows == 0)
  {
    $conn->close();
    
    add_log($username, 'Login user does not exist');
    
    goto label_exit;
  }
  
  $stmt->fetch();
  
  if (! $user['active'])
  {
    
    add_log($username, 'Login user inactive');
    
    goto label_exit;
  }
  
  if (password_verify($password, $user['password']))
  {
    
    // update user last login
    $sql = 'UPDATE tbl_user SET user_last_sign_on = NOW()  WHERE user_id = "' . $user['id'] . '"';
    
    if ($result = $conn->query($sql))
    {
      
      $conn->close();
      
      $_SESSION['userid'] = $user['id'];
      $_SESSION['username'] = $user['name'];
      
      add_log($username, 'Login success');
      
      $success = true;
      goto label_exit;
    }
    else
    {
      
      add_log($username, 'Login issue');
      
      goto label_exit;
    }
    
    $result->free();
  }
  else
  {
    
    add_log($username, 'Login wrong password');
  }
  
  label_exit:
  return $success;
  
}

function add_log($user, $action)
{
  
  require_once 'connect.php';
  
  $conn = connectDB();
  
  if ($conn->connect_error)
  {
    $conn->close();
    return false;
  }
  
  if (isset($_SESSION['userid']))
  {
    
    $user = $_SESSION['userid'];
  }
  
  $sql = 'CALL add_log("' . $user . '","' . $action . '")';
  
  if ($result = $conn->query($sql))
  {
    
    $conn->close();
    
    return true;
  }
  else
  {
    
    return false;
  }
  
  $result->free();
  
}

?>