<?php
namespace util;

class Database
{
  
  private $databaseConnection;
  
  private $querySql;
  
  private $queryResult;
  
  private $errorMessage;
  
  private function connect(): void
  {
    
    $this->databaseConnection = null;
    
    $db_hostname = 'localhost';
    $db_database = 'task_tracker_prod';
    $db_username = 'tasks_guest';
    $db_password = '9QkYzuG2ToMaCXji';
    
    $this->databaseConnection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
    
  }
  
  public function runQuery(string $querySql)
  {
    
    unset($this->queryResult);
    
    $this->querySql = $querySql;
    
    $this->connect();
    
    // catch connect error
    if (! $this->databaseConnection)
    {
      
      $this->setErrorMessage('Could not connect to database');
      
      die($this->errorMessage);
      
      return false;
    }
    elseif ($this->databaseConnection->connect_error)
    {
      
      die($this->errorMessage);
      
      $this->setErrorMessage('Could not connect to database');
      
      die($this->errorMessage);
      
      return false;
    }
    
    $this->queryResult = $this->databaseConnection->query($querySql);
    
    $this->databaseConnection->close();
    
    return true;
    
  }
  
  public function getQueryResults(): object
  {
    
    return $this->queryResult;
    
  }
  
  private function setErrorMessage($errMsg): void
  {
    
    $this->errorMessage = $errMsg;
    
  }
  
}

?>