<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Common\AbstractEntity;

class TblAccountKey extends AbstractEntity
{

    /**
     * @Column(type="string")
     */
    protected $ValidFrom;

    /**
     * @Column(type="string")
     */
    protected $Value;

    /**
     * @Column(type="string")
     */
    protected $ValidTo;

    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @Column(type="string")
     */
    protected $Code;

    /**
     * @return string $validFrom
     */
    public function getValidFrom()
    {
        return $this->ValidFrom;
    }

    /**
     * @param string $validFrom
     */
    public function setValidFrom($validFrom)
    {

        $this->ValidFrom = $validFrom;
    }

    /**
     * @return string $value
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {

        $this->Value = $value;
    }

    /**
     * @return string $validTo
     */
    public function getValidTo()
    {

        return $this->ValidTo;
    }

    /**
     * @param string $validTo
     */
    public function setValidTo($validTo)
    {

        $this->ValidTo = $validTo;
    }

    /**
     * @return string description
     */
    public function getDescription()
    {

        return $this->Description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {

        $this->Description = $description;
    }

    /**
     * @return string code
     */
    public function getCode()
    {

        return $this->Code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {

        $this->Code = $code;
    }
}