<?php namespace test; session_start();?>
<!DOCTYPE html>
<html>

<body>

	<div class="main">

<?php

class classTest
{
  
  private $recurType;
  
  private $recurSepCount;
  
  private $recurDayOfWeek;
  
  private $recurWeekOfMonth;
  
  private $recurDayOfMonth;
  
  private $recurMonthOfYear;
  
  private $sepMax;
  
  const sepCountWeekly = 51;
  
  const sepCountMonthly = 11;
  
  const sepCountAnnually = 1;
  
  public function setRecurrenceType($type)
  {
    
    $this->recurType = $type;
    
    switch ($type)
    {
      case 1:
        $sepMax = sepCountWeekly;
        
        break;
      case 2:
        $sepMax = sepCountMonthly;
        
        break;
      case 3:
        $sepMax = sepCountAnnually;
        
        break;
      default:
        // do nothing
        break;
    }
    
    $recurSepCount = array();
    for ($i = 1; $i <= $sepMax; $i ++)
    {
      $recurSepCount = $i;
    }
    
    echo print_r($recurSepCount);
    
  }
  
}

$something = new classTest();
$something->setRecurrenceType(1);

?>

</div>

</body>

</html>