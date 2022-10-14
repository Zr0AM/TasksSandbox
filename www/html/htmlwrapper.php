<?php
namespace html;

define("crLfTab", "");
define("activeClass", ' class="active"');

class htmlwrapper
{
  
  public static function writeHeader($current_page)
  {
    
    self::writeHtmlHeader();
    self::writeMenu($current_page);
    self::updateLastActivity();
    
  }
  
  private static function writeMenu($current_page): void
  {
    
    echo '<div class="scrollmenu">';
    
    echo crLfTab;
    
    echo '<a ';
    if (htmlspecialchars($current_page) == '/login.php')
    {
      echo 'href="login.php" class="active">Login';
    }
    elseif (isset($_SESSION['userid']))
    {
      echo 'href="logout.php">Logout';
    }
    else
    {
      echo 'href="login.php">Login';
    }
    echo '</a>' . crLfTab;
    
    echo '<a href="index.php"';
    if (htmlspecialchars($current_page) == '/index.php')
    {
      echo activeClass;
    }
    echo '>Home</a>' . crLfTab;
    
    if (isset($_SESSION['userid']))
    {
      echo '<a href="task_queue.php"';
      
      if (htmlspecialchars($current_page) == '/task_queue.php')
      {
        echo activeClass;
      }
      
      echo '>Task Queue</a>' . crLfTab;
    }
    
    if (isset($_SESSION['userid']))
    {
      echo '<a href="all_tasks.php"';
      
      if (htmlspecialchars($current_page) == '/all_tasks.php')
      {
        echo activeClass;
      }
      
      echo '>Task Overview</a>' . crLfTab;
    }
    
    echo '<a href="about_us.php"';
    if (htmlspecialchars($current_page) == '/about_us.php')
    {
      echo activeClass;
    }
    echo '>About Us</a>';
    
    echo '</div>' . "\r\n";
    
    echo '<div class="main">' . "\r\n";
    
  }
  
  private static function updateLastActivity(): void
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
  
  private static function writeHtmlHeader(): void
  
  {
    
    echo '<!DOCTYPE html>' . "\r\n";
    echo '<html lang="en">' . "\r\n";
    
    // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    // header("Cache-Control: post-check=0, pre-check=0", false);
    // header("Pragma: no-cache");
    
    echo '<head>' . "\r\n";
    echo "\t" . '<meta charset="UTF-8">' . "\r\n";
    echo "\t" . '<title>Omnomnom Tasks</title>' . "\r\n";
    echo "\t" . '<meta name="author" content="d4c4dc089f1b0073f2634b479413010658e07b2c75bc939e256dafb3e6c1c0a441484f667a27ce60bf895992724dfc2c309efa5726d91c7939c4fc597e7fd233">' . "\r\n";
    echo "\t" . '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">' . "\r\n";
    echo "\t" . '<link rel="stylesheet" type="text/css" href="style/style.css">' . "\r\n";
    echo "\t" . '<script src="js/script.js"></script>' . "\r\n";
    echo "\t" . '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">' . "\r\n";
    echo '</head>' . "\r\n" . "\r\n";
    
    echo '<body>' . "\r\n";
    
  }
  
  public static function writeFooter(): void
  {
    
    echo "\r\n";
    
    echo '</div>' . "\r\n";
    
    echo '</body>' . "\r\n";
    
    echo '</html>';
    
  }
  
}

?>