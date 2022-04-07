<?php
namespace www\util;

class Dropdown
{
  
  private string $strDisplayName;
  
  private string $strObjName;
  
  private string $strId;
  
  private string $strOnChange;
  
  private int $iSize;
  
  private $strOptions = array();
  
  private int $iDefaultOption;
  
  function getDefaultOption()
  {
    
    return $this->iDefaultOption;
    
  }
  
  function setDefaultOption($iDefaultOption)
  {
    
    $this->iDefaultOption = $iDefaultOption;
    
  }
  
  function getObjName(): string
  {
    
    return $this->strObjName;
    
  }
  
  function getId(): string
  {
    
    return $this->strId;
    
  }
  
  function getOnChange(): string
  {
    
    return $this->strOnChange;
    
  }
  
  function setObjName($strObjName)
  {
    
    $this->strObjName = $strObjName;
    
  }
  
  function setId($strId)
  {
    
    $this->strId = $strId;
    
  }
  
  function setOnChange($strOnChange)
  {
    
    $this->strOnChange = $strOnChange;
    
  }
  
  function addOptions($arrayIn): void
  {
    
    $this->strOptions = $arrayIn;
    $this->iSize = count($arrayIn);
    
  }
  
  function size(): int
  {
    
    return $this->iSize;
    
  }
  
  function setDisplayName(string $strIn): void
  {
    
    $this->strDisplayName = $strIn;
    
  }
  
  function getDisplayName(): string
  {
    
    return $this->strDisplayName;
    
  }
  
  function getHTML(): string
  {
    
    $strOutput = "<form>" . $this->strDisplayName . "&nbsp;<select name=" . '"' . $this->strObjName . '"' . " ";
    $strOutput = $strOutput . "onchange=" . '"' . $this->strOnChange . '"' . " id=" . '"' . $this->strId . '"' . ">";
    
    foreach ($this->strOptions as $value)
    {
      if ($this->strOptions[$this->iDefaultOption] != $value)
      {
        $strOutput = $strOutput . "<option value=" . '"' . $value . '"' . ">" . $value . "</option>";
      }
      else
      {
        $strOutput = $strOutput . "<option value=" . '"' . $value . '"' . " selected=" . '"' . "selected" . '"' . ">" . $value . "</option>";
      }
    }
    
    $strOutput = $strOutput . "</select></form>";
    
    return $strOutput;
    
  }
  
}

?>