<?php
namespace KREDA\Sphere\Application\Management\Service\Address\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;

/**
 * @Entity
 * @Table(name="tblAddress")
 * @Cache(usage="READ_ONLY")
 */
class TblAddress
{

    const ATTR_STREET_NAME = 'StreetName';
    const ATTR_STREET_Number = 'StreetNumber';
    const ATTR_POST_OFFICE_BOX = 'PostOfficeBox';
    const ATTR_TBL_ADDRESS_CITY = 'tblAddressCity';
    const ATTR_TBL_ADDRESS_STATE = 'tblAddressState';
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $StreetName;
    /**
     * @Column(type="string")
     */
    private $StreetNumber;
    /**
     * @Column(type="string")
     */
    private $PostOfficeBox;
    /**
     * @Column(type="bigint")
     */
    private $tblAddressCity;
    /**
     * @Column(type="bigint")
     */
    private $tblAddressState;

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
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }

    /**
     * @return bool|TblAddressCity
     */
    public function getTblAddressCity()
    {

        return Management::serviceAddress()->entityAddressCityById( $this->tblAddressCity );
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

        return Management::serviceAddress()->entityAddressStateById( $this->tblAddressState );
    }

    /**
     * @param null|TblAddressState $tblAddressState
     */
    public function setTblAddressState( TblAddressState $tblAddressState = null )
    {

        $this->tblAddressState = ( null === $tblAddressState ? null : $tblAddressState->getId() );
    }
}
