<?php
namespace KREDA\Sphere\Application\Billing\Service\Account\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccount")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblAccount extends AbstractEntity
{

    const ATTR_TBL_ACCOUNT_TYPE = 'tblAccountType';

    /**
     * @Column(type="string")
     */
    protected $Number;
    /**
     * @Column(type="string")
     */
    protected $Description;
    /**
     * @Column(type="float")
     */
    protected $Value;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccountType;

    /**
     * @return string
     */
    public function getNumber()
    {

        return $this->Number;
    }

    /**
     * @param string $Number
     */
    public function setNumber( $Number )
    {

        $this->Number = $Number;
    }

    /**
     * @return string
     */
    public function getDescription()
    {

        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription( $Description )
    {

        $this->Description = $Description;
    }

    /**
     * @return float
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param float $Value
     */
    public function setValue( $Value )
    {

        $this->Value = $Value;
    }

    /**
     * @return bool|TblAccountType
     */
    public function getTblAccountType()
    {

        if (null === $this->tblAccountType) {
            return false;
        } else {
            return Billing::serviceAccount()->entityAccountTypeById( $this->tblAccountType );
        }
    }

    /**
     * @param null|TblAccountType $tblAccountType
     */
    public function setTblAccountType( TblAccountType $tblAccountType = null )
    {

        $this->tblAccountType = ( null === $tblAccountType ? null : $tblAccountType->getId() );
    }
}
