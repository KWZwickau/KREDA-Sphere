<?php
namespace KREDA\Sphere\Application\Management\Service\Address\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAddress")
 * @Cache(usage="READ_ONLY")
 */
class TblAddress extends AbstractEntity
{

    const ATTR_STREET_NAME = 'StreetName';
    const ATTR_STREET_NUMBER = 'StreetNumber';
    const ATTR_POST_OFFICE_BOX = 'PostOfficeBox';
    const ATTR_TBL_ADDRESS_CITY = 'tblAddressCity';
    const ATTR_TBL_ADDRESS_STATE = 'tblAddressState';

    /**
     * @Column(type="string")
     */
    protected $StreetName;
    /**
     * @Column(type="string")
     */
    protected $StreetNumber;
    /**
     * @Column(type="string")
     */
    protected $PostOfficeBox;
    /**
     * @Column(type="bigint")
     */
    protected $tblAddressCity;
    /**
     * @Column(type="bigint")
     */
    protected $tblAddressState;

    /**
     * @return mixed
     */
    public function getStreetName()
    {

        return $this->StreetName;
    }

    /**
     * @param mixed $StreetName
     */
    public function setStreetName( $StreetName )
    {

        $this->StreetName = $StreetName;
    }

    /**
     * @return mixed
     */
    public function getStreetNumber()
    {

        return $this->StreetNumber;
    }

    /**
     * @param mixed $StreetNumber
     */
    public function setStreetNumber( $StreetNumber )
    {

        $this->StreetNumber = $StreetNumber;
    }

    /**
     * @return mixed
     */
    public function getPostOfficeBox()
    {

        return $this->PostOfficeBox;
    }

    /**
     * @param mixed $PostOfficeBox
     */
    public function setPostOfficeBox( $PostOfficeBox )
    {

        $this->PostOfficeBox = $PostOfficeBox;
    }

    /**
     * @return bool|TblAddressCity
     */
    public function getTblAddressCity()
    {

        if (null === $this->tblAddressCity) {
            return false;
        } else {
            return Management::serviceAddress()->entityAddressCityById( $this->tblAddressCity );
        }
    }

    /**
     * @param null|TblAddressCity $tblAddressCity
     */
    public function setTblAddressCity( TblAddressCity $tblAddressCity = null )
    {

        $this->tblAddressCity = ( null === $tblAddressCity ? null : $tblAddressCity->getId() );
    }

    /**
     * @return bool|TblAddressState
     */
    public function getTblAddressState()
    {

        if (null === $this->tblAddressState) {
            return false;
        } else {
            return Management::serviceAddress()->entityAddressStateById( $this->tblAddressState );
        }
    }

    /**
     * @param null|TblAddressState $tblAddressState
     */
    public function setTblAddressState( TblAddressState $tblAddressState = null )
    {

        $this->tblAddressState = ( null === $tblAddressState ? null : $tblAddressState->getId() );
    }
}
