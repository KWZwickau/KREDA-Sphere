<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

class TblAccountKey extends AbstractEntity
{

    const ATTR_TBL_ACCOUNT_KEY_TYPE = 'tblAccountKeyType';

    /**
     * @Column(type="date")
     */
    protected $ValidFrom;
    /**
     * @Column(type="decimal")
     */
    protected $Value;
    /**
     * @Column(type="date")
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
     * @Column(type="bigint")
     */
    protected $tblAccountKeyType;

    /**
     * @return date $validFrom
     */
    public function getValidFrom()
    {
        return $this->ValidFrom;
    }

    /**
     * @param date $validFrom
     */
    public function setValidFrom($validFrom)
    {

        $this->ValidFrom = $validFrom;
    }

    /**
     * @return decimal $value
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param decimal $value
     */
    public function setValue($value)
    {

        $this->Value = $value;
    }

    /**
     * @return date $validTo
     */
    public function getValidTo()
    {

        return $this->ValidTo;
    }

    /**
     * @param date $validTo
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

    /**
     * @return bool|TblAccountKeyType
     */
    public function getTableAccountKey()
    {

        if (null === $this->tblAccountKeyType ){
            return false;
        } else {
            return Billing::serviceAccount()->entityAccountKeyTypeById( $this->tblAccountKeyType);
        }
    }

    /**
     * @param bool|TblAccountKeyType $tblAccountKeyType
     */
    public function setTableAccountKeyType( tblAccountKeyType $tblAccountKeyType = null )
    {
        $this->tblAccountKeyType = ( null === $tblAccountKeyType ? null : $tblAccountKeyType->getId() );
    }
}