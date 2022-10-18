<?php
namespace util;

class Dropdown
{

    private string $strDisplayName;

    private string $strObjName;

    private string $strId;

    private string $strOnChange;

    private int $iSize;

    private $strOptions = array();

    private int $iDefaultOption;

    public function getDefaultOption()
    {
        return $this->iDefaultOption;
    }

    public function setDefaultOption($iDefaultOption)
    {
        $this->iDefaultOption = $iDefaultOption;
    }

    public function getObjName(): string
    {
        return $this->strObjName;
    }

    public function getId(): string
    {
        return $this->strId;
    }

    public function getOnChange(): string
    {
        return $this->strOnChange;
    }

    public function setObjName($strObjName)
    {
        $this->strObjName = $strObjName;
    }

    public function setId($strId)
    {
        $this->strId = $strId;
    }

    public function setOnChange($strOnChange)
    {
        $this->strOnChange = $strOnChange;
    }

    public function addOptions($arrayIn): void
    {
        $this->strOptions = $arrayIn;
        $this->iSize = count($arrayIn);
    }

    public function size(): int
    {
        return $this->iSize;
    }

    public function setDisplayName(string $strIn): void
    {
        $this->strDisplayName = $strIn;
    }

    public function getDisplayName(): string
    {
        return $this->strDisplayName;
    }

    public function getHTML(): string
    {
        $strOutput = "<form>" . $this->strDisplayName . "&nbsp;<select name=" . '"' . $this->strObjName . '"' . " ";
        $strOutput = $strOutput . "onchange=" . '"' . $this->strOnChange . '"' . " id=" . '"' . $this->strId . '"' . ">";

        foreach ($this->strOptions as $value) {
            if ($this->strOptions[$this->iDefaultOption] != $value) {
                $strOutput = $strOutput . "<option value=" . '"' . $value . '"' . ">" . $value . "</option>";
            } else {
                $strOutput = $strOutput . "<option value=" . '"' . $value . '"' . " selected=" . '"' . "selected" . '"' . ">" . $value . "</option>";
            }
        }

        $strOutput = $strOutput . "</select></form>";

        return $strOutput;
    }
}
